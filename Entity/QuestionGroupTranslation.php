<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\QuestionGroupTranslationRepository")
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
    
}
