<?php

namespace FanFerret\QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="FanFerret\QuestionBundle\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 */
class User extends \FOS\UserBundle\Model\User
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Acl",mappedBy="user")
     */
    private $acls;

    /**
     * Add acl
     *
     * @param \FanFerret\QuestionBundle\Entity\Acl $acl
     *
     * @return User
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
