<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\QuestionAnswerRepository")
 * @ORM\Table(name="question_answer")
 */
class QuestionAnswer
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="SurveySession",inversedBy="questionAnswers")
     */
    private $surveySession;
    
    /**
     * @ORM\ManyToOne(targetEntity="Question",inversedBy="questionAnswers")
     */
    private $question;
    
    /**
     * @ORM\Column(type="text")
     */
    private $value;
    
}
