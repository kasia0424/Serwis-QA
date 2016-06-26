<?php
/**
 * Data fixture for Category entity.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/categories
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Category;

/**
 * Class LoadCategoryData
 * @package AppBundle\DataFixtures\ORM
 * @author Wanda Sipel
 */
class LoadCategoryData implements FixtureInterface
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
        $categories = array('flat', 'social', 'sell/buy', 'job');
        foreach ($categories as $category) {
            $obj = new Category();
            $obj->setName($category);
            $manager->persist($obj);
        }
        $manager->flush();
    }
}
