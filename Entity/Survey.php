<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\SurveyRepository")
 * @ORM\Table(name="survey")
 */
class Survey
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $companyId;
    
    /**
     * @ORM\OneToMany(targetEntity="SurveyTranslation",mappedBy="survey")
     */
    private $translations;
    
    /**
     * @ORM\Column(type="string",length=255)
     */
    private $slug;
    /**
     * @ORM\OneToMany(targetEntity="QuestionGroup",mappedBy="survey")
     */
    private $questionGroups;
    
}
