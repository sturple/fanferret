<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\RuleRepository")
 * @ORM\Table(name="rule")
 */
class Rule
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToMany(targetEntity="Question",inversedBy="rules")
     * @ORM\JoinTable(name="questions_rules")
     */
    private $questions;
	
	/**
	 * @ORM\Column(type="text")
	 */
	private $params;
    
}
