<?php
/**
 * Data fixture for Role entity.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/roles
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Role;

/**
 * Class LoadRoleData
 * @package AppBundle\DataFixtures\ORM
 * @author Wanda Sipel
 */
class LoadRoleData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $roles = array('ROLE_USER', 'ROLE_ADMIN');
        foreach ($roles as $role) {
            $obj = new Role();
            $obj->setName($role);
            $manager->persist($obj);
        }
        $manager->flush();
    }
}
