<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="FanFerret\QuestionBundle\Repository\SurveySessionRepository")
 * @ORM\Table(name="survey_session",uniqueConstraints={@ORM\UniqueConstraint(name="token_idx",columns={"token"})})
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
     * @ORM\Column(type="integer")
     * 
     */
    private $surveyId;
    
    /**
     * @ORM\ManyToOne(targetEntity="Survey",inversedBy="surveySessions")
     */
    private $survey;
    
    /**
     * @ORM\Column(type="string",length=128,nullable=true)
     */
    private $room;
    
    /**
     * @ORM\Column(type="string",length=128)
     */
    private $token;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $created;
    
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $completed;
    
    /**
     * @ORM\OneToMany(targetEntity="QuestionAnswer",mappedBy="surveySession",cascade="all")
     */
    private $questionAnswers;
    
    /**
     * @ORM\OneToMany(targetEntity="SurveyNotification",mappedBy="surveySession")
     */
    private $surveyNotifications;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $checkout;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $seen;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string",length=128)
     */
    private $email;

    /**
     * @ORM\Column(type="string",length=128,nullable=true)
     */
    private $language;
    
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
    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return SurveySession
     */
    public function setCreated($created)
    {
        $this->created = \FanFerret\QuestionBundle\Utility\DateTime::toDoctrine($created);

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created = \FanFerret\QuestionBundle\Utility\DateTime::fromDoctrine($this->created);
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
        $this->completed = \FanFerret\QuestionBundle\Utility\DateTime::toDoctrine($completed);

        return $this;
    }

    /**
     * Get completed
     *
     * @return \DateTime
     */
    public function getCompleted()
    {
        return $this->completed = \FanFerret\QuestionBundle\Utility\DateTime::fromDoctrine($this->completed);
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

    /**
     * Set token
     *
     * @param string $token
     *
     * @return SurveySession
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
     * Set checkout
     *
     * @param \DateTime $checkout
     *
     * @return SurveySession
     */
    public function setCheckout($checkout)
    {
        $this->checkout = \FanFerret\QuestionBundle\Utility\DateTime::toDoctrine($checkout);

        return $this;
    }

    /**
     * Get checkout
     *
     * @return \DateTime
     */
    public function getCheckout()
    {
        return $this->checkout = \FanFerret\QuestionBundle\Utility\DateTime::fromDoctrine($this->checkout);
    }

    /**
     * Set seen
     *
     * @param \DateTime $seen
     *
     * @return SurveySession
     */
    public function setSeen($seen)
    {
        $this->seen = \FanFerret\QuestionBundle\Utility\DateTime::toDoctrine($seen);

        return $this;
    }

    /**
     * Get seen
     *
     * @return \DateTime
     */
    public function getSeen()
    {
        return $this->seen = \FanFerret\QuestionBundle\Utility\DateTime::fromDoctrine($this->seen);
    }

    /**
     * Set room
     *
     * @param string $room
     *
     * @return SurveySession
     */
    public function setRoom($room)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return string
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return SurveySession
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return SurveySession
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return SurveySession
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return SurveySession
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
}
