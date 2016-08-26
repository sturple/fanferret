<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="FanFerret\QuestionBundle\Repository\QuestionGroupTranslationRepository")
 * @ORM\Table(name="question_group_translation")
 */
class QuestionGroupTranslation
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="QuestionGroup",inversedBy="translations")
     */
    private $questionGroup;
    
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
     * @return QuestionGroupTranslation
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
     * @return QuestionGroupTranslation
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
     * Set questionGroup
     *
     * @param \FanFerret\QuestionBundle\Entity\QuestionGroup $questionGroup
     *
     * @return QuestionGroupTranslation
     */
    public function setQuestionGroup(\FanFerret\QuestionBundle\Entity\QuestionGroup $questionGroup = null)
    {
        $this->questionGroup = $questionGroup;

        return $this;
    }

    /**
     * Get questionGroup
     *
     * @return \FanFerret\QuestionBundle\Entity\QuestionGroup
     */
    public function getQuestionGroup()
    {
        return $this->questionGroup;
    }
}
