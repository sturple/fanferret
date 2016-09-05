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
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $slugGroup;
    
    /**
     * @ORM\OneToMany(targetEntity="QuestionGroup",mappedBy="survey")
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
     * Set slugGroup
     *
     * @param string $slugGroup
     *
     * @return Survey
     */
    public function setSlugGroup($slugGroup)
    {
        $this->slugGroup = $slugGroup;

        return $this;
    }

    /**
     * Get slugGroup
     *
     * @return string
     */
    public function getSlugGroup()
    {
        return $this->slugGroup;
    }
}
