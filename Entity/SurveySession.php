<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="FanFerret\QuestionBundle\Repository\SurveySessionRepository")
 * @ORM\Table(name="survey_session",uniqueConstraints={@ORM\UniqueConstraint(name="key_idx",columns={"key"})})
 */
class SurveySession
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Survey",inversedBy="surveySessions")
     */
    private $survey;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $guestId;
    
    /**
     * @ORM\Column(type="string",length=32)
     */
    private $key;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $created;
    
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $completed;
    
    /**
     * @ORM\OneToMany(targetEntity="QuestionAnswer",mappedBy="surveySession")
     */
    private $questionAnswers;
    
    /**
     * @ORM\OneToMany(targetEntity="SurveyNotification",mappedBy="surveySession")
     */
    private $surveyNotifications;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questionAnswers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->surveyNotifications = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set guestId
     *
     * @param integer $guestId
     *
     * @return SurveySession
     */
    public function setGuestId($guestId)
    {
        $this->guestId = $guestId;

        return $this;
    }

    /**
     * Get guestId
     *
     * @return integer
     */
    public function getGuestId()
    {
        return $this->guestId;
    }

    /**
     * Set key
     *
     * @param string $key
     *
     * @return SurveySession
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return SurveySession
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set completed
     *
     * @param \DateTime $completed
     *
     * @return SurveySession
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed
     *
     * @return \DateTime
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set survey
     *
     * @param \FanFerret\QuestionBundle\Entity\Survey $survey
     *
     * @return SurveySession
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
     * Add questionAnswer
     *
     * @param \FanFerret\QuestionBundle\Entity\QuestionAnswer $questionAnswer
     *
     * @return SurveySession
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
     * Add surveyNotification
     *
     * @param \FanFerret\QuestionBundle\Entity\SurveyNotification $surveyNotification
     *
     * @return SurveySession
     */
    public function addSurveyNotification(\FanFerret\QuestionBundle\Entity\SurveyNotification $surveyNotification)
    {
        $this->surveyNotifications[] = $surveyNotification;

        return $this;
    }

    /**
     * Remove surveyNotification
     *
     * @param \FanFerret\QuestionBundle\Entity\SurveyNotification $surveyNotification
     */
    public function removeSurveyNotification(\FanFerret\QuestionBundle\Entity\SurveyNotification $surveyNotification)
    {
        $this->surveyNotifications->removeElement($surveyNotification);
    }

    /**
     * Get surveyNotifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSurveyNotifications()
    {
        return $this->surveyNotifications;
    }
}
