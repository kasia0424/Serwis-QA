<?php
/**
 * Question repository.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/questions
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class Question.
 * @package AppBundle\Repository
 * @author Wanda Sipel
 */
class Question extends EntityRepository
{
    /**
     * Save question object.
     *
     * @param Question $question Question object
     */
    public function save(\AppBundle\Entity\Question $question)
    {
        $this->_em->persist($question);
        $this->_em->flush();
    }

    /**
     * Delete question object.
     *
     * @param Question $question Question object
     */
    public function delete(\AppBundle\Entity\Question $question)
    {
        $this->_em->remove($question);
        $this->_em->flush();
    }
}
