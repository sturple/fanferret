<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FanFerret\QuestionBundle\Utility\Json as Json;

/**
 * @ORM\Entity(repositoryClass="FanFerret\QuestionBundle\Repository\PropertyRepository")
 * @ORM\Table(name="property")
 */
class Property
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $params;

    /**
     * @ORM\ManyToOne(targetEntity="Group",inversedBy="properties")
     */
    private $group;

    /**
     * @ORM\OneToMany(targetEntity="Survey",mappedBy="property")
     */
    private $surveys;

    /**
     * @ORM\OneToMany(targetEntity="Acl",mappedBy="property")
     */
    private $acls;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->surveys = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Property
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Property
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set params
     *
     * @param object $params
     *
     * @return Question
     */
    public function setParams($params)
    {
        if (!is_object($params)) throw new \InvalidArgumentException('$params not object');
        $this->params=Json::encode($params);

        return $this;
    }

    /**
     * Get params
     *
     * @return object
     */
    public function getParams()
    {
        $retr=Json::decode($this->params);
        if (!is_object($retr)) throw new \LogicException('$params not JSON object');
        return $retr;
    }

    /**
     * Set group
     *
     * @param \FanFerret\QuestionBundle\Entity\Group $group
     *
     * @return Property
     */
    public function setGroup(\FanFerret\QuestionBundle\Entity\Group $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \FanFerret\QuestionBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Add survey
     *
     * @param \FanFerret\QuestionBundle\Entity\Survey $survey
     *
     * @return Property
     */
    public function addSurvey(\FanFerret\QuestionBundle\Entity\Survey $survey)
    {
        $this->surveys[] = $survey;

        return $this;
    }

    /**
     * Remove survey
     *
     * @param \FanFerret\QuestionBundle\Entity\Survey $survey
     */
    public function removeSurvey(\FanFerret\QuestionBundle\Entity\Survey $survey)
    {
        $this->surveys->removeElement($survey);
    }

    /**
     * Get surveys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSurveys()
    {
        return $this->surveys;
    }

    /**
     * Add acl
     *
     * @param \FanFerret\QuestionBundle\Entity\Acl $acl
     *
     * @return Property
     */
    public function addAcl(\FanFerret\QuestionBundle\Entity\Acl $acl)
    {
        $this->acls[] = $acl;

        return $this;
    }

    /**
     * Remove acl
     *
     * @param \FanFerret\QuestionBundle\Entity\Acl $acl
     */
    public function removeAcl(\FanFerret\QuestionBundle\Entity\Acl $acl)
    {
        $this->acls->removeElement($acl);
    }

    /**
     * Get acls
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAcls()
    {
        return $this->acls;
    }
}
