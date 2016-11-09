<?php
/**
 * Created by PhpStorm.
 * User: vinid
 * Date: 26/07/15
 * Time: 20.35
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wikidata relation.
 *
 * @ORM\Table(name="wikidata_qualifier_relation")
 * @ORM\Entity
 */
class QualifierWikidataRelation  extends Relation
{
    /**
     */
    public function getRelationType()
    {
        return 'wikidata_qualifier';
    }
}