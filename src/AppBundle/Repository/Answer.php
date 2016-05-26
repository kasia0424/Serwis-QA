<?php
/**
 * Answer repository.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/answers
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class Answer.
 * @package AppBundle\Repository
 * @author Wanda Sipel
 */
class Answer extends EntityRepository
{
    /**
     * Save answer object.
     *
     * @param Answer $answer Answer object
     */
    public function save(\AppBundle\Entity\Answer $answer)
    {
        $this->_em->persist($answer);
        $this->_em->flush();
    }

    /**
     * Delete answer object.
     *
     * @param Answer $answer Answer object
     */
    public function delete(\AppBundle\Entity\Answer $answer)
    {
        $this->_em->remove($answer);
        $this->_em->flush();
    }
}
