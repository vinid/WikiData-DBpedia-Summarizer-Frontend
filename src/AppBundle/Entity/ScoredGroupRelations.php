<?php
/**
 * Created by PhpStorm.
 * User: vinid
 * Date: 25/07/15
 * Time: 14.35
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Ser;


/**
 * Relation.
 *
 * @ORM\Table(name="scored_group_relations")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class ScoredGroupRelations
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @var double
     *
     * @ORM\Column(name="similarity", type="float")
     *
     */
    private $similarity;

    /**
     * @var string
     *
     * @ORM\Column(name="metric", type="string", length=255)
     *
     */
    private $metric;

    /**
     * @ORM\OneToMany(targetEntity="Relation", mappedBy="scored",cascade={"persist", "remove"})
     **/
    private $relations;

    /**
     * @ORM\ManyToOne(targetEntity="EntityEvaluation", inversedBy="scoredGroupRelations",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="entity_id", referencedColumnName="id")
     *
     * @Ser\Exclude
     **/
    private $entityEvaluation;

    public function __construct()
    {
        $this->relations = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return float
     */
    public function getSimilarity()
    {
        return $this->similarity;
    }

    /**
     * @param float $similarity
     */
    public function setSimilarity($similarity)
    {
        $this->similarity = $similarity;
    }

    public function addRelation(Relation $value)
    {
        $this->relations->add($value);
    }

    /**
     * @return mixed
     */
    public function getEntityEvaluation()
    {
        return $this->entityEvaluation;
    }

    /**
     * @param mixed $entityEvaluation
     */
    public function setEntityEvaluation($entityEvaluation)
    {
        $this->entityEvaluation = $entityEvaluation;
    }

    /**
     * @return mixed
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @param mixed $relations
     */
    public function setRelations($relations)
    {
        $this->relations = $relations;
    }

    /**
     * @return string
     */
    public function getMetric()
    {
        return $this->metric;
    }

    /**
     * @param string $metric
     */
    public function setMetric($metric)
    {
        $this->metric = $metric;
    }


}