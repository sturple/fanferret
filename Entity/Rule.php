<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\RuleRepository")
 * @ORM\Table(name="rule")
 */
class Rule
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToMany(targetEntity="Question",inversedBy="rules")
     * @ORM\JoinTable(name="questions_rules")
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
     * Set params
     *
     * @param string $params
     *
     * @return Rule
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get params
     *
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Add question
     *
     * @param \QuestionBundle\Entity\Question $question
     *
     * @return Rule
     */
    public function addQuestion(\QuestionBundle\Entity\Question $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param \QuestionBundle\Entity\Question $question
     */
    public function removeQuestion(\QuestionBundle\Entity\Question $question)
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
