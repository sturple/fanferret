<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\SurveyTranslationRepository")
 * @ORM\Table(name="survey_translation")
 */
class SurveyTranslation
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Survey",inversedBy="translations")
     */
    private $survey;
    
    /**
     * @ORM\Column(type="string",length=255)
     */
    private $language;
    
    /**
     * @ORM\Column(type="text")
     */
    private $text;
    
}
