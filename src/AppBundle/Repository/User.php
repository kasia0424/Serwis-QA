<?php
/**
 * User repository.
 *
 * @copyright (c) 2016 Agnieszka Gorgolewska
 * @link http://wierzba.wzks.uj.edu.pl/~12_gorgolewska
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * Class User.
 * @package AppBundle\Repository
 * @author Agnieszka Gorgolewska
 */
class User extends EntityRepository
{

    /**
     * Save user object.
     *
     * @param User $user User object
     */
    public function save(\AppBundle\Entity\User $user)
    {

        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Delete user object
     *
     * @param User $user User object
     */
    public function delete(\AppBundle\Entity\User $user)
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }

    /**
     * Load user by name
     * @param $username
     * @return array
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param UserInterface $user
     * @return null|object
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());
    }


    /**
     * @param $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
        || is_subclass_of($class, $this->getEntityName());
    }

    /**
     * Add default role
     * @param $user
     * @return array
     */
    public function addDefaultRole($user)
    {
        $role = $this->getEntityManager()
            ->getRepository('AppBundle:Role')
            ->findOneBy(array('role' => 'ROLE_USER'));

        $user->addRole($role);
        $this->save($user);
    }
}
