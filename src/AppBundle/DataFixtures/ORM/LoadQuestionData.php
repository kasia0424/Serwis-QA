<?php
/**
 * Data fixture for Question entity.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/questions
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Question;

/**
 * Class LoadQuestionData
 * @package AppBundle\DataFixtures\ORM
 * @author Wanda Sipel
 */
class LoadQuestionData implements FixtureInterface
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
        $question = new Question();
        $question->setTitle('test');
        $question->setContent('some content');
        $question->setCreatedAt(new \DateTime());

        $tagRent = $manager->getRepository('AppBundle:Tag')
            ->findOneByName('rent');
        $tagBestseller = $manager->getRepository('AppBundle:Tag')
            ->findOneByName('bestseller');
        $question->addTag($tagRent);
        $question->addTag($tagBestseller);

        //to sledzi obiekty, czy sie nie zmieniaja
        $manager->persist($tagRent);
        $manager->persist($tagBestseller);
        $manager->persist($question);
        
        $manager->flush();
    }
}
