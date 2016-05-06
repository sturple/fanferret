<?php

namespace QuestionBundle\Entity;

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
    
}
