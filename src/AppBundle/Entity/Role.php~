<?php
/**
 * Role entity.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/roles
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Role.
 *
 * @package Model
 * @author WS
 *
 * @ORM\Table(name="qa_roles")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Role")
 * @UniqueEntity(fields="name", groups={"role-default"})
 */
class Role
{
    /**
     * Id
     *
     * @ORM\Id
     * @ORM\Column(
     *     type="integer",
     *     nullable=false,
     *     options={
     *         "unsigned" = true
     *     }
     * )
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    private $id;

    /**
     * Name
     *
     * @ORM\Column(
     *     name="name",
     *     type="string",
     *     length=128,
     *     nullable=false
     * )
     * @Assert\NotBlank(groups={"role-default"})
     * @Assert\Length(min=3, max=128, groups={"role-default"})
     *
     * @var string $name
     */
    private $name;

    // /**
     // * Users array
     // *
     // * @ORM\ManyToMany(targetEntity="User", mappedBy="role")
     // *
     // * @var \Doctrine\Common\Collections\ArrayCollection $users
     // */
    // protected $users;
    
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param string $id
     * @return Role
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * @return Role
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
     * Add users.
     *
     * @param \AppBundle\Entity\User $users
     */
    public function addUser(\AppBundle\Entity\User $users)
    {
        $this->users[] = $users;
    }

    /**
     * Remove users
     *
     * @param \AppBundle\Entity\User $users
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
    
    /**
     * Get all records.
     *
     * @access public
     * @return array Roles array
     */
    public function findAll()
    {
        return $this->roles;
    }

    /**
     * Get single record by its id.
     *
     * @access public
     * @param integer $id Single record index
     * @return array Result
     */
    public function find($id)
    {
        if (isset($this->roles[$id]) && count($this->roles)) {
            return $this->roles[$id];
        } else {
            return array();
        }
    }
    
    /**
     * Delete single record by its id.
     *
     * @access public
     * @param integer $role Single record index
     * @return array Result
     */
    public function delete($role)
    {
        return $this->remove($role);
        //$this->sections->removeElement($sections);
    }
}
