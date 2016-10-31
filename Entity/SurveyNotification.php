<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="FanFerret\QuestionBundle\Repository\SurveyNotificationRepository")
 * @ORM\Table(name="survey_notification",uniqueConstraints={@ORM\UniqueConstraint(name="token_idx",columns={"token"})})
 */
class SurveyNotification
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="SurveySession",inversedBy="surveyNotifications")
     */
    private $surveySession;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	private $sent;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $seen;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="string",length=128)
     */
    private $token;

    /**
     * @ORM\Column(type="string",length=128)
     */
    private $contentType;

    /**
     * @ORM\Column(type="text")
     */
    private $subject;
    

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
     * Set surveySession
     *
     * @param \FanFerret\QuestionBundle\Entity\SurveySession $surveySession
     *
     * @return SurveyNotification
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
     * Set seen
     *
     * @param \DateTime $seen
     *
     * @return SurveyNotification
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
     * Set body
     *
     * @param string $body
     *
     * @return SurveyNotification
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set sent
     *
     * @param \DateTime $sent
     *
     * @return SurveyNotification
     */
    public function setSent($sent)
    {
        $this->sent = \FanFerret\QuestionBundle\Utility\DateTime::toDoctrine($sent);

        return $this;
    }

    /**
     * Get sent
     *
     * @return \DateTime
     */
    public function getSent()
    {
        return $this->sent = \FanFerret\QuestionBundle\Utility\DateTime::fromDoctrine($this->sent);
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return SurveyNotification
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
     * Set contentType
     *
     * @param string $contentType
     *
     * @return SurveyNotification
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return SurveyNotification
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
