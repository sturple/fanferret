<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FanFerret\QuestionBundle\Utility\Json as Json;

/**
 * @ORM\Entity(repositoryClass="FanFerret\QuestionBundle\Repository\QuestionGroupRepository")
 * @ORM\Table(name="question_group")
 */
class QuestionGroup
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="integer")
     * 
     */
    private $surveyId;
    
    /**
     * @ORM\ManyToOne(targetEntity="Survey",inversedBy="questionGroups")
     */
    private $survey;
    
    /**
     * @ORM\Column(type="integer",name="`order`")
     */
    private $order;
    
    /**
     * @ORM\OneToMany(targetEntity="Question",mappedBy="questionGroup",cascade="all")
     * @ORM\OrderBy({"order"="ASC"})
     */
    private $questions;
    
    /**
     * @ORM\Column(type="text")
     */
    private $params;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set order
     *
     * @param integer $order
     *
     * @return QuestionGroup
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set survey
     *
     * @param \FanFerret\QuestionBundle\Entity\Survey $survey
     *
     * @return QuestionGroup
     */
    public function setSurvey(\FanFerret\QuestionBundle\Entity\Survey $survey = null)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * Get survey
     *
     * @return \FanFerret\QuestionBundle\Entity\Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Add question
     *
     * @param \FanFerret\QuestionBundle\Entity\Question $question
     *
     * @return QuestionGroup
     */
    public function addQuestion(\FanFerret\QuestionBundle\Entity\Question $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param \FanFerret\QuestionBundle\Entity\Question $question
     */
    public function removeQuestion(\FanFerret\QuestionBundle\Entity\Question $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }
    
    /**
     * Set params
     *
     * @param object $params
     *
     * @return QuestionGroup
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
     * Get suveyId
     *
     * @return integer
     */
    public function getSurveyId()
    {
        return $this->surveyId;
    }
    
     /**
     * Set surveyId
     *
     * @param integer $surveyid
     *
     * @return SurveySession
     */
    public function setSurveyId($surveyid)
    {
        $this->surveyId = $surveyid;
        return $this;    
    }       
}
