<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\SurveySessionRepository")
 * @ORM\Table(name="survey_session",uniqueConstraints={@ORM\UniqueConstraint(name="key_idx",column={"key"})})
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
     * @ORM\OneToMany(targetEntity="QuestionAnswer",inversedBy="surveySession")
     */
    private $questionAnswers;
    
}
