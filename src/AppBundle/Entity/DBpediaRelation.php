<?php
/**
 * Created by PhpStorm.
 * User: vinid
 * Date: 25/07/15
 * Time: 15.16
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * DBpedia relation.
 *
 * @ORM\Table(name="dbpedia_relation")
 * @ORM\Entity
 */
class DBpediaRelation extends Relation  {

    /**
     */
    public function getRelationType()
    {
        return 'dbpedia';
    }

}