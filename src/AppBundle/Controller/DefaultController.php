<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DBpediaRelation;
use AppBundle\Entity\EntityEvaluation;
use AppBundle\Entity\QualifierWikidataRelation;
use AppBundle\Entity\ScoredGroupRelations;
use AppBundle\Entity\WikidataRelation;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this -> getDoctrine() -> getManager();

        $results  = $em -> getRepository("AppBundle:EntityEvaluation") -> findAll();

        return $this->render('index.html.twig', array("entities" => $results) );
    }

    /**
     * @Route("/show/{id}", name="show_entity")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showEntityAction($id)
    {
        $em = $this -> getDoctrine() -> getManager();
        $results  = $em -> getRepository("AppBundle:EntityEvaluation") -> find($id);

        return $this->render('show_entity.html.twig', array("results" => $results) );
    }

    /**
     * @Route("/process", name="process")
     * @Method({"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function processAction(Request $request)
    {
        $fp = fsockopen("localhost", 4309, $errno, $errstr, 100);
        $string = "";
        $query = $request->request->get('myquery');


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

        $results = json_decode($string, true);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $entityName = $results ["entity"];
        /*       $old = $em -> getRepository("AppBundle:EntityEvaluation") ->findOneBy(array('entity' => $entityName));

               //rimuovi l'entità se già esiste
               if ($old != null)
                   $em->remove($old);
       */
        $dbpediaDescription = $results ["dbpediaDescription"];
        $wikidataDescription = $results["wikidataDescription"];
        $entityAbstract = $results["dbpediaAbstract"];
        $totalDbProperties = $results["totalDbProperties"];
        $totalWdProperties = $results["totalWdProperties"];
        $sameProperties = $results["sameProperties"];

        $entity = new EntityEvaluation();
        $entity->setEntity($entityName);
        $entity->setDbpediaAbstract($entityAbstract);
        $entity->setDescriptionDBpedia($dbpediaDescription);
        $entity->setDescriptionWikidata($wikidataDescription);
        $entity->setTotalDbProperties($totalDbProperties);
        $entity->setTotalWdProperties($totalWdProperties);
        $entity->setSameProperties($sameProperties);
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
                $strpos = strpos($i["object"], "^^");

                if($strpos != 0)
                {
                    $relation->setObject( substr($i["object"], 0, $strpos ));
                }

                $relation->setScored($scored);
                $scored->addRelation($relation);

                if(isset($i["qualifiers"]))
                {
                    foreach ($i["qualifiers"] as $qualiPair)
                    {
                        $qualiRelation = new QualifierWikidataRelation();
                        $qualiRelation -> setProperty($qualiPair["property"]);

                        $qualiRelation->setObject($qualiPair["object"]);
                        $strpos = strpos($qualiPair["object"], "^^");

                        if($strpos != 0)
                        {
                            $qualiRelation->setObject( substr($qualiPair["object"], 0, $strpos  ));
                        }


                        $qualiRelation -> setIsQualifierOf($relation);
                        $relation -> addQualifier($qualiRelation);
                    }
                }
            }

            foreach ($jso["properties"]["object"] as $v) {
                $relation = new DBpediaRelation();
                $relation->setProperty($v["property"]);
                $relation->setObject($v["object"]);
                $strpos = strpos($v["object"], "^^");

                if($strpos != 0)
                {
                    $relation->setObject( substr($v["object"], 0, $strpos  ));
                }


                $relation->setScored($scored);
                $scored->addRelation($relation);
                $em->persist($relation);
            }
            $entity->addScored($scored);
            $em->flush();


        }
        $em->persist($entity);
        $em->flush();

        $results  = $em -> getRepository("AppBundle:EntityEvaluation") -> find($entity ->getId());


        return $this->render('show_entity.html.twig', array("results" => $results) );
    }
}
