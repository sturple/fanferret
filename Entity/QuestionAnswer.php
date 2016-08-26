<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\QuestionAnswerRepository")
 * @ORM\Table(name="question_answer")
 */
class QuestionAnswer
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="SurveySession",inversedBy="questionAnswers")
     */
    private $surveySession;
    
    /**
     * @ORM\ManyToOne(targetEntity="Question",inversedBy="questionAnswers")
     */
    private $question;
    
    /**
     * @ORM\Column(type="text")
     */
    private $value;

    /**
     * @ORM\OneToOne(targetEntity="Testimonial",mappedBy="questionAnswer")
     */
    private $testimonial;
    

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
     * Set value
     *
     * @param string $value
     *
     * @return QuestionAnswer
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set surveySession
     *
     * @param \FanFerret\QuestionBundle\Entity\SurveySession $surveySession
     *
     * @return QuestionAnswer
     */
    public function setSurveySession(\FanFerret\QuestionBundle\Entity\SurveySession $surveySession = null)
    {
        $this->surveySession = $surveySession;

        return $this;
    }

    /**
     * Get surveySession
     *
     * @return \FanFerret\QuestionBundle\Entity\SurveySession
     */
    public function getSurveySession()
    {
        return $this->surveySession;
    }

    /**
     * Set question
     *
     * @param \FanFerret\QuestionBundle\Entity\Question $question
     *
     * @return QuestionAnswer
     */
    public function setQuestion(\FanFerret\QuestionBundle\Entity\Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \FanFerret\QuestionBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set testimonial
     *
     * @param \FanFerret\QuestionBundle\Entity\Testimonial $testimonial
     *
     * @return QuestionAnswer
     */
    public function setTestimonial(\FanFerret\QuestionBundle\Entity\Testimonial $testimonial = null)
    {
        $this->testimonial = $testimonial;

        return $this;
    }

    /**
     * Get testimonial
     *
     * @return \FanFerret\QuestionBundle\Entity\Testimonial
     */
    public function getTestimonial()
    {
        return $this->testimonial;
    }
}
