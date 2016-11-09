<?php
/**
 * Created by PhpStorm.
 * User: vinid
 * Date: 25/07/15
 * Time: 15.18
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Wikidata relation.
 *
 * @ORM\Table(name="dbpedia_relation")
 * @ORM\Entity
 */
class WikidataRelation extends Relation {
    /**
     */
    public function getRelationType()
    {
        return 'wikidata';
    }
}