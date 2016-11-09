<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\DBpediaRelation;
use AppBundle\Entity\EntityEvaluation;
use AppBundle\Entity\QualifierWikidataRelation;
use AppBundle\Entity\ScoredGroupRelations;
use AppBundle\Entity\WikidataRelation;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


use Symfony\Component\HttpFoundation\Response;

class DefaultController extends FOSRestController
{
    /**
     * @var Serializer
     * @DI\Inject("jms_serializer")
     */
    public $serializer;

    /**
     * @param $query
     * @return array
     */
    public function getAction($query)
    {
        $fp = fsockopen("localhost", 4309, $errno, $errstr, 100);
        $string = "";

        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {

            fwrite($fp, $query . "\n\r");
            while (!feof($fp)) {
                $string .= fgets($fp, 2048);
            }

            fclose($fp);
        }

        //pulisco:
        $string = str_replace("http://dbpedia.org/resource/", "", $string);

        $view = new Response($string);
        return ($view);
    }

    public function prettyGetAction($query)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array('entityEvaluation', 'isQualifierOf', 'id'));
        $normalizer->setCircularReferenceHandler(function ($object) {
            return "";
        });
        $normalizers = array($normalizer);

        $serializer = new Serializer($normalizers, $encoders);

        $fp = fsockopen("localhost", 4309, $errno, $errstr, 100);
        $string = "";


        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {

            fwrite($fp, $query . "\n\r");
            while (!feof($fp)) {
                $string .= fgets($fp, 2048);
            }

            fclose($fp);
        }

        //pulisco:
        $string = str_replace("http://dbpedia.org/resource/", "", $string);

        $string = mb_convert_encoding($string, "UTF-8");

        //rimuovo i link che portano a schema nelle date
        $string = str_replace("^^http://www.w3.org/2001/XMLSchema#date", "", $string);

        //rimuovo i link che portano a schema nelle date (questo è di wikidata, che riporta anche le ore...)
        $string = str_replace("T00:00:00Z^^http://www.w3.org/2001/XMLSchema#dateTime", "", $string);

        $results = json_decode($string, true);

        $entityName = $results ["entity"];
        /*       $old = $em -> getRepository("AppBundle:EntityEvaluation") ->findOneBy(array('entity' => $entityName));

               //rimuovi l'entità se già esiste
               if ($old != null)
                   $em->remove($old);
       */
        $dbpediaDescription = $results ["dbpediaDescription"];
        $wikidataDescription = $results["wikidataDescription"];
        $entityAbstract = $results["dbpediaAbstract"];

        $entity = new EntityEvaluation();
        $entity->setEntity($entityName);
        $entity->setDbpediaAbstract($entityAbstract);
        $entity->setDescriptionDBpedia($dbpediaDescription);
        $entity->setDescriptionWikidata($wikidataDescription);
        $json = $results ["pairs"];
        foreach ($json as $jso) {
            $scored = new ScoredGroupRelations();
            $scored->setEntityEvaluation($entity);
            $scored->setSimilarity($jso["similarity"]);
            $scored -> setMetric($jso["metric"]);

            foreach ($jso["properties"]["property"] as $i) {
                $relation = new WikidataRelation();
                $relation->setProperty($i["property"]);
                $relation->setObject($i["object"]);
                $relation -> setObjectUrl($i["uriObject"]);
                $relation -> setPropertyUrl("uriProperty");
                $relation->setScored($scored);
                $scored->addRelation($relation);

                if(isset($i["qualifiers"]))
                {
                    foreach ($i["qualifiers"] as $qualiPair)
                    {
                        $qualiRelation = new QualifierWikidataRelation();
                        $qualiRelation->setProperty($qualiPair["property"]);
                        $qualiRelation->setObject($qualiPair["object"]);
                        $qualiRelation -> setIsQualifierOf($relation);
                        $relation -> addQualifier($qualiRelation);
                    }
                }
            }

            foreach ($jso["properties"]["object"] as $v) {
                $relation = new DBpediaRelation();
                $relation->setProperty($v["property"]);
                $relation->setObject($v["object"]);
                $relation -> setObjectUrl($v["uriObject"]);
                $relation -> setPropertyUrl($v["uriProperty"]);
                $relation->setScored($scored);
                $scored->addRelation($relation);
            }
            $entity->addScored($scored);


        }



        $data = $serializer->serialize($entity, 'json');

        return new Response ($data);
    }
}
