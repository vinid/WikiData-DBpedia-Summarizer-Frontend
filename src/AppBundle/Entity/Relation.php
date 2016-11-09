<?php
/**
 * Created by PhpStorm.
 * User: vinid
 * Date: 25/07/15
 * Time: 12.25
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Relation.
 *
 * @ORM\Table(name="relation")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="relation_type", type="string", length=20)
 * @ORM\DiscriminatorMap({"dbpedia" = "DBpediaRelation", "wikidata" = "WikidataRelation", "wikidata_qualifier" = "QualifierWikidataRelation"})
 */
abstract class Relation {

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
     * @var string
     *
     * @ORM\Column(name="property", type="string", length=255)
     *
     */
    private $property;

    /**
     * @var string
     *
     * @ORM\Column(name="propertyURL", type="string", length=255, nullable = true)
     *
     */
    private $propertyUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="obj", type="string", length=255)
     *
     */
    private $object;

    /**
     * @var string
     *
     * @ORM\Column(name="objURL", type="string", length=255, nullable = true)
     *
     */
    private $objectUrl;

    /**
     * @ORM\OneToMany(targetEntity="Relation", mappedBy="isQualifierOf",cascade={"persist", "remove"})
     **/
    private $qualifiers;

    /**
     * @ORM\ManyToOne(targetEntity="Relation", inversedBy="qualifiers",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="qualifier_id", referencedColumnName="id")
     **/
    private $isQualifierOf;

    /**
     * @ORM\ManyToOne(targetEntity="ScoredGroupRelations", inversedBy="relations",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="scored_id", referencedColumnName="id")
     **/
    private $scored;


    /**
     *
     */
    abstract public function getRelationType();


    function __construct()
    {
        $this -> qualifiers = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param mixed $property
     */
    public function setProperty($property)
    {
        $this->property = $property;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param mixed $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return mixed
     */
    public function getQualifiers()
    {
        return $this->qualifiers;
    }

    /**
     * @param mixed $qualifiers
     */
    public function setQualifiers($qualifiers)
    {
        $this->qualifiers = $qualifiers;
    }

    /**
     * @return string
     */
    public function getPropertyUrl()
    {
        return $this->propertyUrl;
    }

    /**
     * @param string $propertyUrl
     */
    public function setPropertyUrl($propertyUrl)
    {
        $this->propertyUrl = $propertyUrl;
    }

    /**
     * @return string
     */
    public function getObjectUrl()
    {
        return $this->objectUrl;
    }

    /**
     * @param string $objectUrl
     */
    public function setObjectUrl($objectUrl)
    {
        $this->objectUrl = $objectUrl;
    }

    /**
     * @return mixed
     */
    public function getScored()
    {
        return $this->scored;
    }

    /**
     * @param mixed $scored
     */
    public function setScored($scored)
    {
        $this->scored = $scored;
    }

    public function addQualifier($quali)
    {
        $this -> qualifiers -> add($quali);
    }

    /**
     * @return mixed
     */
    public function getIsQualifierOf()
    {
        return $this->isQualifierOf;
    }

    /**
     * @param mixed $isQualifierOf
     */
    public function setIsQualifierOf($isQualifierOf)
    {
        $this->isQualifierOf = $isQualifierOf;
    }


}