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
     * Set order
     *
     * @param integer $order
     *
     * @return Question
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set params
     *
     * @param string $params
     *
     * @return Question
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get params
     *
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set questionGroup
     *
     * @param \QuestionBundle\Entity\QuestionGroup $questionGroup
     *
     * @return Question
     */
    public function setQuestionGroup(\QuestionBundle\Entity\QuestionGroup $questionGroup = null)
    {
        $this->questionGroup = $questionGroup;

        return $this;
    }

    /**
     * Get questionGroup
     *
     * @return \QuestionBundle\Entity\QuestionGroup
     */
    public function getQuestionGroup()
    {
        return $this->questionGroup;
    }
}
