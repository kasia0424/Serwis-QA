<?php
/**
 * Data fixture for Tag entity.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/tags
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Tag;

/**
 * Class LoadTagData
 * @package AppBundle\DataFixtures\ORM
 * @author Wanda Sipel
 */
class LoadTagData implements FixtureInterface
{
    /**
     * Load function
     *
     * @param ObjectManager $manager Object manager
     *
     * @return mixed
     */
    public function load(ObjectManager $manager)
    {
        $tags = array('ocasion', 'bestseller', 'cat', 'rent');
        foreach ($tags as $tag) {
            $obj = new Tag();
            $obj->setName($tag);
            $manager->persist($obj);
        }
        $manager->flush();
    }
}
