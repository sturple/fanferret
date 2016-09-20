<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FanFerret\QuestionBundle\Utility\Json as Json;

/**
 * @ORM\Entity(repositoryClass="FanFerret\QuestionBundle\Repository\GroupRepository")
 * @ORM\Table(name="`group`")
 */
class Group
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $params;

    /**
     * @ORM\OneToMany(targetEntity="Property",mappedBy="group")
     */
    private $properties;

    /**
     * @ORM\OneToMany(targetEntity="Acl",mappedBy="group")
     */
    private $acls;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->properties = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Group
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Group
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
     * Set params
     *
     * @param object $params
     *
     * @return Question
     */
    public function setParams($params)
    {
        if (!is_object($params)) throw new \InvalidArgumentException('$params not object');
        $this->params=Json::encode($params);

        return $this;
    }

    /**
     * Get params
     *
     * @return object
     */
    public function getParams()
    {
        $retr=Json::decode($this->params);
        if (!is_object($retr)) throw new \LogicException('$params not JSON object');
        return $retr;
    }

    /**
     * Add property
     *
     * @param \FanFerret\QuestionBundle\Entity\Property $property
     *
     * @return Group
     */
    public function addProperty(\FanFerret\QuestionBundle\Entity\Property $property)
    {
        $this->properties[] = $property;

        return $this;
    }

    /**
     * Remove property
     *
     * @param \FanFerret\QuestionBundle\Entity\Property $property
     */
    public function removeProperty(\FanFerret\QuestionBundle\Entity\Property $property)
    {
        $this->properties->removeElement($property);
    }

    /**
     * Get properties
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Add acl
     *
     * @param \FanFerret\QuestionBundle\Entity\Acl $acl
     *
     * @return Group
     */
    public function addAcl(\FanFerret\QuestionBundle\Entity\Acl $acl)
    {
        $this->acls[] = $acl;

        return $this;
    }

    /**
     * Remove acl
     *
     * @param \FanFerret\QuestionBundle\Entity\Acl $acl
     */
    public function removeAcl(\FanFerret\QuestionBundle\Entity\Acl $acl)
    {
        $this->acls->removeElement($acl);
    }

    /**
     * Get acls
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAcls()
    {
        return $this->acls;
    }
}
