<?php
/**
 * Tag repository.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/tags
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class Tag.
 * @package AppBundle\Repository
 * @author Wanda Sipel
 */
class Tag extends EntityRepository
{
    /**
     * Save tag object.
     *
     * @param Tag $tag Tag object
     */
    public function save(\AppBundle\Entity\Tag $tag)
    {
        $this->_em->persist($tag);
        $this->_em->flush();
    }

    /**
     * Delete tag object.
     *
     * @param Tag $tag Tag object
     */
    public function delete(\AppBundle\Entity\Tag $tag)
    {
        $this->_em->remove($tag);
        $this->_em->flush();
    }
}
