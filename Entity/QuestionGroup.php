<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\QuestionGroupRepository")
 * @ORM\Table(name="question_group")
 */
class QuestionGroup
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Survey",inversedBy="questionGroups")
     */
    private $survey;
    
    /**
     * @ORM\OneToMany(targetEntity="QuestionGroupTranslation",mappedBy="questionGroup")
     */
    private $translations;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $order;
    
    /**
     * @ORM\OneToMany(targetEntity="Question",mappedBy="questionGroup")
     */
    private $questions;
    
}
