<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="FanFerret\QuestionBundle\Repository\AclRepository")
 * @ORM\Table(name="acl")
 */
class Acl
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="acls")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Survey",inversedBy="acls")
     */
    private $survey;

    /**
     * @ORM\ManyToOne(targetEntity="Property",inversedBy="acls")
     */
    private $property;

    /**
     * @ORM\ManyToOne(targetEntity="Group",inversedBy="acls")
     */
    private $group;

    /**
     * @ORM\Column(type="string",length=128)
     */
    private $role;

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
     * Set role
     *
     * @param string $role
     *
     * @return Acl
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set user
     *
     * @param \FanFerret\QuestionBundle\Entity\User $user
     *
     * @return Acl
     */
    public function setUser(\FanFerret\QuestionBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \FanFerret\QuestionBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set survey
     *
     * @param \FanFerret\QuestionBundle\Entity\Survey $survey
     *
     * @return Acl
     */
    public function setSurvey(\FanFerret\QuestionBundle\Entity\Survey $survey = null)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * Get survey
     *
     * @return \FanFerret\QuestionBundle\Entity\Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Set property
     *
     * @param \FanFerret\QuestionBundle\Entity\Property $property
     *
     * @return Acl
     */
    public function setProperty(\FanFerret\QuestionBundle\Entity\Property $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return \FanFerret\QuestionBundle\Entity\Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set group
     *
     * @param \FanFerret\QuestionBundle\Entity\Group $group
     *
     * @return Acl
     */
    public function setGroup(\FanFerret\QuestionBundle\Entity\Group $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \FanFerret\QuestionBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }
}
