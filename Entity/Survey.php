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
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set companyId
     *
     * @param integer $companyId
     *
     * @return Survey
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * Get companyId
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Survey
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add translation
     *
     * @param \QuestionBundle\Entity\SurveyTranslation $translation
     *
     * @return Survey
     */
    public function addTranslation(\QuestionBundle\Entity\SurveyTranslation $translation)
    {
        $this->translations[] = $translation;

        return $this;
    }

    /**
     * Remove translation
     *
     * @param \QuestionBundle\Entity\SurveyTranslation $translation
     */
    public function removeTranslation(\QuestionBundle\Entity\SurveyTranslation $translation)
    {
        $this->translations->removeElement($translation);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Add questionGroup
     *
     * @param \QuestionBundle\Entity\QuestionGroup $questionGroup
     *
     * @return Survey
     */
    public function addQuestionGroup(\QuestionBundle\Entity\QuestionGroup $questionGroup)
    {
        $this->questionGroups[] = $questionGroup;

        return $this;
    }

    /**
     * Remove questionGroup
     *
     * @param \QuestionBundle\Entity\QuestionGroup $questionGroup
     */
    public function removeQuestionGroup(\QuestionBundle\Entity\QuestionGroup $questionGroup)
    {
        $this->questionGroups->removeElement($questionGroup);
    }

    /**
     * Get questionGroups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestionGroups()
    {
        return $this->questionGroups;
    }
}
