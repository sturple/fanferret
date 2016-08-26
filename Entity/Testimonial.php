<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="FanFerret\QuestionBundle\Repository\TestimonialRepository")
 * @ORM\Table(name="testimonial")
 */
class Testimonial
{

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\OneToOne(targetEntity="QuestionAnswer",inversedBy="testimonial")
	 */
	private $questionAnswer;

	/**
	 * @ORM\Column(type="string",length=128,nullable=true)
	 */
	private $token;

	/**
	 * @ORM\Column(type="boolean",nullable=false)
	 */
	private $approved;

	/**
	 * @ORM\Column(type="text",nullable=false)
	 */
	private $text;
	

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
     * Set token
     *
     * @param string $token
     *
     * @return Testimonial
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     *
     * @return Testimonial
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Testimonial
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set questionAnswer
     *
     * @param \FanFerret\QuestionBundle\Entity\QuestionAnswer $questionAnswer
     *
     * @return Testimonial
     */
    public function setQuestionAnswer(\FanFerret\QuestionBundle\Entity\QuestionAnswer $questionAnswer = null)
    {
        $this->questionAnswer = $questionAnswer;

        return $this;
    }

    /**
     * Get questionAnswer
     *
     * @return \FanFerret\QuestionBundle\Entity\QuestionAnswer
     */
    public function getQuestionAnswer()
    {
        return $this->questionAnswer;
    }
}
