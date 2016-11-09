<?php
/**
 * Created by PhpStorm.
 * User: vinid
 * Date: 26/07/15
 * Time: 10.37
 */

namespace AppBundle\Twig;

use AppBundle\Entity\DBpediaRelation;
use AppBundle\Entity\Relation;
use AppBundle\Entity\WikidataRelation;

class TwigAbstractRelationExtension extends \Twig_Extension
{
    public function getTests ()
    {
        return [
            new \Twig_SimpleTest('dbpediaRelation', function (Relation $event) { return $event instanceof DBpediaRelation; }),
            new \Twig_SimpleTest('wikidataRelation', function (Relation $event) { return $event instanceof WikidataRelation; })
        ];
    }

    public function getName()
    {
        return 'appbudle_relation_extension';
    }
}