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
     * Set language
     *
     * @param string $language
     *
     * @return SurveyTranslation
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
     * Set text
     *
     * @param string $text
     *
     * @return SurveyTranslation
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
     * Set survey
     *
     * @param \QuestionBundle\Entity\Survey $survey
     *
     * @return SurveyTranslation
     */
    public function setSurvey(\QuestionBundle\Entity\Survey $survey = null)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * Get survey
     *
     * @return \QuestionBundle\Entity\Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }
}
