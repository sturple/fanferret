<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\SurveyNotificationRepository")
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
}
