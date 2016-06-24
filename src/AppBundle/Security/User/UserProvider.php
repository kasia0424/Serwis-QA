<?php
namespace AppBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\Common\Persistence\ObjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class WebserviceUserProvider
 * @package AppBundle\Security\User *
 *
 * @Route(service="app.webservice_user_provider")
 */
class WebserviceUserProvider implements UserProviderInterface
{

    /**
     * Model object.
     *
     * @var ObjectRepository $model
     */
    private $model;
    public function __construct(
        ObjectRepository $model
    ) {
    

        $this->model = $model;

    }

    public function loadUserByUsername($username)
    {

        $user = $this->model->findOneBy($username);





        return new WebserviceUser(
            $user->getUsername(),
            $user->getPassword(),
            '',
            $user->getRoles()
        );


        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'AppBundle\Security\User\WebserviceUser';
    }
}
