<?php
/**
 * Created by PhpStorm.
 * User: vinid
 * Date: 25/07/15
 * Time: 15.22
 */

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Ser;

/**
 * Relation.
 *
 * @ORM\Table(name="entity_evaluation")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 *
 */
class EntityEvaluation
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Ser\Expose
     *
     */
    private $id;

    /**
     * @Ser\Expose
     * @ORM\OneToMany(targetEntity="ScoredGroupRelations", mappedBy="entityEvaluation", cascade={"persist", "remove"})
     **/
    private $scoredGroupRelations;

    /**
     * @var string
     *
     * @Ser\Expose
     * @ORM\Column(name="entity", type="string", length=255)
     *
     */
    private $entity;

    /**
     * @var string
     *
     * @ORM\Column(name="dbpedia_abstract", type="text")
     *
     * @Ser\Expose
     */
    private $dbpediaAbstract;

    /**
     * @var string
     *
     * @ORM\Column(name="description_dbpedia", type="text")
     *
     * @Ser\Expose
     */
    private $descriptionDBpedia;

    /**
     * @var string
     *
     * @ORM\Column(name="description_wikidata", type="text")
     *
     * @Ser\Expose
     *
     */
    private $descriptionWikidata;

    /**
     * @var int
     *
     * @ORM\Column(name="total_db_properties", type="decimal")
     *
     * @Ser\Expose
     */
    private $totalDbProperties;

    /**
     * @var int
     *
     * @ORM\Column(name="total_wd_properties", type="decimal")
     *
     * @Ser\Expose
     */
    private $totalWdProperties;

    /**
     * @var int
     *
     * @ORM\Column(name="same_properties", type="decimal")
     *
     * @Ser\Expose
     */
    private $sameProperties;

    public function __construct()
    {
        $this -> scoredGroupRelations = new ArrayCollection();
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
     * @return mixed
     */
    public function getScoredGroupRelations()
    {
        return $this->scoredGroupRelations;
    }

    /**
     * @param mixed $scoredGroupRelations
     */
    public function setScoredGroupRelations($scoredGroupRelations)
    {
        $this->scoredGroupRelations = $scoredGroupRelations;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param string $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getDescriptionDBpedia()
    {
        return $this->descriptionDBpedia;
    }

    /**
     * @param string $descriptionDBpedia
     */
    public function setDescriptionDBpedia($descriptionDBpedia)
    {
        $this->descriptionDBpedia = $descriptionDBpedia;
    }

    /**
     * @return string
     */
    public function getDescriptionWikidata()
    {
        return $this->descriptionWikidata;
    }

    /**
     * @param string $descriptionWikidata
     */
    public function setDescriptionWikidata($descriptionWikidata)
    {
        $this->descriptionWikidata = $descriptionWikidata;
    }

    public function addScored($scored)
    {
        $this -> scoredGroupRelations -> add($scored);
    }

    /**
     * @return string
     */
    public function getDbpediaAbstract()
    {
        return $this->dbpediaAbstract;
    }

    /**
     * @param string $dbpediaAbstract
     */
    public function setDbpediaAbstract($dbpediaAbstract)
    {
        $this->dbpediaAbstract = $dbpediaAbstract;
    }

    /**
     * @return int
     */
    public function getTotalDbProperties()
    {
        return $this->totalDbProperties;
    }

    /**
     * @param int $totalDbProperties
     */
    public function setTotalDbProperties($totalDbProperties)
    {
        $this->totalDbProperties = $totalDbProperties;
    }

    /**
     * @return int
     */
    public function getTotalWdProperties()
    {
        return $this->totalWdProperties;
    }

    /**
     * @param int $totalWdProperties
     */
    public function setTotalWdProperties($totalWdProperties)
    {
        $this->totalWdProperties = $totalWdProperties;
    }

    /**
     * @return int
     */
    public function getSameProperties()
    {
        return $this->sameProperties;
    }

    /**
     * @param int $sameProperties
     */
    public function setSameProperties($sameProperties)
    {
        $this->sameProperties = $sameProperties;
    }

}