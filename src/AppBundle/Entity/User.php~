<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * @ORM\Table(name="qa_users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * Id
     *
     * @ORM\Column(type="integer",
     *     nullable=false,
     *     options={
     *         "unsigned" = true
     *     }
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\Column(
     *      name="birthdate",
     *      type="datetime", 
     *      nullable=true
     * )
     */
    protected $birthdate;
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->roles = array('ROLE_USER');
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        $this->usernameCanonical = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->emailCanonical = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return User
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }
    
    // public function addRole($role)
    // {
        // $role = strtoupper($role);
        // if ($role === static::ROLE_USER) {
            // return $this;
        // }

        // if (!in_array($role, $this->roles, true)) {
            // $this->roles[] = $role;
        // }

        // return $this;
    // }
    
    // /**
     // * Returns the user roles
     // *
     // * @return array The roles
     // */
    // public function getRoles()
    // {
        // $roles = $this->roles;

        // foreach ($this->getGroups() as $group) {
            // $roles = array_merge($roles, $group->getRoles());
        // }

        // // we need to make sure to have at least one role
        // $roles[] = static::ROLE_USER;

        // return array_unique($roles);
    // }

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    protected $plainPassword;
    
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        return $this;
    }

    public function getPlainPassword()
    {

        return $this->plainPassword;
    }
}
