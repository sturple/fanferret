<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="FanFerret\QuestionBundle\Repository\SurveyNotificationRepository")
 * @ORM\Table(name="survey_notification")
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
	private $when;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $seen;

    /**
     * @ORM\Column(type="text")
     */
    private $body;
    

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
     * Set when
     *
     * @param \DateTime $when
     *
     * @return SurveyNotification
     */
    public function setWhen($when)
    {
        $this->when = $when;

        return $this;
    }

    /**
     * Get when
     *
     * @return \DateTime
     */
    public function getWhen()
    {
        return $this->when;
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
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen
     *
     * @return \DateTime
     */
    public function getSeen()
    {
        return $this->seen;
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
}
