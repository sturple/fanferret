<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FanFerret\QuestionBundle\Utility\Json as Json;

/**
 * @ORM\Entity(repositoryClass="FanFerret\QuestionBundle\Repository\SurveyRepository")
 * @ORM\Table(name="survey")
 */
class Survey
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
    private $slug;
    
    /**
     * @ORM\OneToMany(targetEntity="QuestionGroup",mappedBy="survey",cascade="all")
     * @ORM\OrderBy({"order"="ASC"})
     */
    private $questionGroups;
    
    /**
     * @ORM\OneToMany(targetEntity="SurveySession",mappedBy="survey")
     */
    private $surveySessions;
    
    /**
     * @ORM\Column(type="text")
     */
    private $params;

    /**
     * @ORM\Column(type="string",length=128)
     */
    private $language;

    /**
     * @ORM\OneToMany(targetEntity="Acl",mappedBy="survey")
     */
    private $acls;

    /**
     * @ORM\ManyToOne(targetEntity="Property",inversedBy="surveys")
     */
    private $property;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $name;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questionGroups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->surveySessions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Survey
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
     * Add questionGroup
     *
     * @param \FanFerret\QuestionBundle\Entity\QuestionGroup $questionGroup
     *
     * @return Survey
     */
    public function addQuestionGroup(\FanFerret\QuestionBundle\Entity\QuestionGroup $questionGroup)
    {
        $this->questionGroups[] = $questionGroup;

        return $this;
    }

    /**
     * Remove questionGroup
     *
     * @param \FanFerret\QuestionBundle\Entity\QuestionGroup $questionGroup
     */
    public function removeQuestionGroup(\FanFerret\QuestionBundle\Entity\QuestionGroup $questionGroup)
    {
        $this->questionGroups->removeElement($questionGroup);
    }

    /**
     * Get questionGroups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestionGroups()
    {
        return $this->questionGroups;
    }

    /**
     * Add surveySession
     *
     * @param \FanFerret\QuestionBundle\Entity\SurveySession $surveySession
     *
     * @return Survey
     */
    public function addSurveySession(\FanFerret\QuestionBundle\Entity\SurveySession $surveySession)
    {
        $this->surveySessions[] = $surveySession;

        return $this;
    }

    /**
     * Remove surveySession
     *
     * @param \FanFerret\QuestionBundle\Entity\SurveySession $surveySession
     */
    public function removeSurveySession(\FanFerret\QuestionBundle\Entity\SurveySession $surveySession)
    {
        $this->surveySessions->removeElement($surveySession);
    }

    /**
     * Get surveySessions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSurveySessions()
    {
        return $this->surveySessions;
    }
    
    /**
     * Set params
     *
     * @param object $params
     *
     * @return Survey
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
     * Set language
     *
     * @param string $language
     *
     * @return Survey
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Add acl
     *
     * @param \FanFerret\QuestionBundle\Entity\User $acl
     *
     * @return Survey
     */
    public function addAcl(\FanFerret\QuestionBundle\Entity\User $acl)
    {
        $this->acls[] = $acl;

        return $this;
    }

    /**
     * Remove acl
     *
     * @param \FanFerret\QuestionBundle\Entity\User $acl
     */
    public function removeAcl(\FanFerret\QuestionBundle\Entity\User $acl)
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

    /**
     * Set property
     *
     * @param \FanFerret\QuestionBundle\Entity\Property $property
     *
     * @return Survey
     */
    public function setProperty(\FanFerret\QuestionBundle\Entity\Property $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return \FanFerret\QuestionBundle\Entity\Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Survey
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
}
