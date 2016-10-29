<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FanFerret\QuestionBundle\Utility\Json as Json;

/**
 * @ORM\Entity(repositoryClass="FanFerret\QuestionBundle\Repository\QuestionRepository")
 * @ORM\Table(name="question")
 */
class Question
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="QuestionGroup",inversedBy="questions")
     */
    private $questionGroup;
    
    /**
     * @ORM\Column(type="integer",name="`order`")
     */
    private $order;
	
	/**
	 * @ORM\Column(type="text")
	 */
	private $params;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Rule",mappedBy="questions",cascade="all")
	 */
	private $rules;
	
    /**
     * @ORM\OneToMany(targetEntity="QuestionAnswer",mappedBy="question")
     */
    private $questionAnswers;

    /**
     * @ORM\Column(type="string",length=128)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="QuestionGroup",inversedBy="questions")
     */
    private $question;

    /**
     * @ORM\OneToMany(targetEntity="Question",mappedBy="question",cascade="all")
     * @ORM\OrderBy({"order"="ASC"})
     */
    private $questions;
	
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rules = new \Doctrine\Common\Collections\ArrayCollection();
        $this->questionAnswers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Question
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
     * Set questionGroup
     *
     * @param \FanFerret\QuestionBundle\Entity\QuestionGroup $questionGroup
     *
     * @return Question
     */
    public function setQuestionGroup(\FanFerret\QuestionBundle\Entity\QuestionGroup $questionGroup = null)
    {
        $this->questionGroup = $questionGroup;

        return $this;
    }

    /**
     * Get questionGroup
     *
     * @return \FanFerret\QuestionBundle\Entity\QuestionGroup
     */
    public function getQuestionGroup()
    {
        return $this->questionGroup;
    }

    /**
     * Add rule
     *
     * @param \FanFerret\QuestionBundle\Entity\Rule $rule
     *
     * @return Question
     */
    public function addRule(\FanFerret\QuestionBundle\Entity\Rule $rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * Remove rule
     *
     * @param \FanFerret\QuestionBundle\Entity\Rule $rule
     */
    public function removeRule(\FanFerret\QuestionBundle\Entity\Rule $rule)
    {
        $this->rules->removeElement($rule);
    }

    /**
     * Get rules
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Add questionAnswer
     *
     * @param \FanFerret\QuestionBundle\Entity\QuestionAnswer $questionAnswer
     *
     * @return Question
     */
    public function addQuestionAnswer(\FanFerret\QuestionBundle\Entity\QuestionAnswer $questionAnswer)
    {
        $this->questionAnswers[] = $questionAnswer;

        return $this;
    }

    /**
     * Remove questionAnswer
     *
     * @param \FanFerret\QuestionBundle\Entity\QuestionAnswer $questionAnswer
     */
    public function removeQuestionAnswer(\FanFerret\QuestionBundle\Entity\QuestionAnswer $questionAnswer)
    {
        $this->questionAnswers->removeElement($questionAnswer);
    }

    /**
     * Get questionAnswers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestionAnswers()
    {
        return $this->questionAnswers;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Question
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set question
     *
     * @param \FanFerret\QuestionBundle\Entity\QuestionGroup $question
     *
     * @return Question
     */
    public function setQuestion(\FanFerret\QuestionBundle\Entity\QuestionGroup $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \FanFerret\QuestionBundle\Entity\QuestionGroup
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Add question
     *
     * @param \FanFerret\QuestionBundle\Entity\Question $question
     *
     * @return Question
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
}
