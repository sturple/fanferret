<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\QuestionRepository")
 * @ORM\Table(name="question")
 */
class Question
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="QuestionGroup",inversedBy="questions")
     */
    private $questionGroup;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $order;
	
	/**
	 * @ORM\Column(type="text")
	 */
	private $params;
	
}
