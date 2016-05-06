<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\QuestionGroupRepository")
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
     * @ORM\ManyToOne(targetEntity="Survey",inversedBy="questionGroups")
     */
    private $survey;
    
    /**
     * @ORM\OneToMany(targetEntity="QuestionGroupTranslation",mappedBy="questionGroup")
     */
    private $translations;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $order;
    
    /**
     * @ORM\OneToMany(targetEntity="Question",mappedBy="questionGroup")
     */
    private $questions;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \QuestionBundle\Entity\Survey $survey
     *
     * @return QuestionGroup
     */
    public function setSurvey(\QuestionBundle\Entity\Survey $survey = null)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * Get survey
     *
     * @return \QuestionBundle\Entity\Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Add translation
     *
     * @param \QuestionBundle\Entity\QuestionGroupTranslation $translation
     *
     * @return QuestionGroup
     */
    public function addTranslation(\QuestionBundle\Entity\QuestionGroupTranslation $translation)
    {
        $this->translations[] = $translation;

        return $this;
    }

    /**
     * Remove translation
     *
     * @param \QuestionBundle\Entity\QuestionGroupTranslation $translation
     */
    public function removeTranslation(\QuestionBundle\Entity\QuestionGroupTranslation $translation)
    {
        $this->translations->removeElement($translation);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Add question
     *
     * @param \QuestionBundle\Entity\Question $question
     *
     * @return QuestionGroup
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
