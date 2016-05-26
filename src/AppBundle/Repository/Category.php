<?php
/**
 * Category repository.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/categories
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class Category.
 * @package AppBundle\Repository
 * @author Wanda Sipel
 */
class Category extends EntityRepository
{
    /**
     * Save category object.
     *
     * @param Category $category Category object
     */
    public function save(\AppBundle\Entity\Category $category)
    {
        $this->_em->persist($category);
        $this->_em->flush();
    }

    /**
     * Delete category object.
     *
     * @param Category $category Category object
     */
    public function delete(\AppBundle\Entity\Category $category)
    {
        $this->_em->remove($category);
        $this->_em->flush();
    }
}
