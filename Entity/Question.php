<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FanFerret\QuestionBundle\Utility\Json as Json;

/**
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\QuestionRepository")
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
     * @ORM\Column(type="integer")
     */
    private $order;
	
	/**
	 * @ORM\Column(type="text")
	 */
	private $params;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Rule",mappedBy="questions")
	 */
	private $rules;
	
    /**
     * @ORM\OneToMany(targetEntity="QuestionAnswer",mappedBy="question")
     */
    private $questionAnswers;
	
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rules = new \Doctrine\Common\Collections\ArrayCollection();
        $this->questionAnswers = new \Doctrine\Common\Collections\ArrayCollection();
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
        if (!is_object($params)) throw new \InvalidArgumentException('$params not string');
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
     * @param \QuestionBundle\Entity\QuestionGroup $questionGroup
     *
     * @return Question
     */
    public function setQuestionGroup(\QuestionBundle\Entity\QuestionGroup $questionGroup = null)
    {
        $this->questionGroup = $questionGroup;

        return $this;
    }

    /**
     * Get questionGroup
     *
     * @return \QuestionBundle\Entity\QuestionGroup
     */
    public function getQuestionGroup()
    {
        return $this->questionGroup;
    }

    /**
     * Add rule
     *
     * @param \QuestionBundle\Entity\Rule $rule
     *
     * @return Question
     */
    public function addRule(\QuestionBundle\Entity\Rule $rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * Remove rule
     *
     * @param \QuestionBundle\Entity\Rule $rule
     */
    public function removeRule(\QuestionBundle\Entity\Rule $rule)
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
     * @param \QuestionBundle\Entity\QuestionAnswer $questionAnswer
     *
     * @return Question
     */
    public function addQuestionAnswer(\QuestionBundle\Entity\QuestionAnswer $questionAnswer)
    {
        $this->questionAnswers[] = $questionAnswer;

        return $this;
    }

    /**
     * Remove questionAnswer
     *
     * @param \QuestionBundle\Entity\QuestionAnswer $questionAnswer
     */
    public function removeQuestionAnswer(\QuestionBundle\Entity\QuestionAnswer $questionAnswer)
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
}
