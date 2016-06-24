<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use Doctrine\Common\Persistence\ObjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends BaseController
{
    /**
     * Questions model object.
     *
     * @var ObjectRepository $questionsModel
     */
    private $questionsModel;

    /**
     * Answers model object.
     *
     * @var ObjectRepository $answersModel
     */
    private $answersModel;
    
     /**
     * QuestionsController constructor.
     *
     * @param ObjectRepository $questionsModel Model object
     * @param ObjectRepository $answersModel Model object
     */
    public function __construct(
        ObjectRepository $questionsModel,
        ObjectRepository $answersModel
    ) {
        $this->questionsModel = $questionsModel;
        $this->answersModel = $answersModel;
    }
    
    
    public function showAction()
    {
        $response = parent::showAction();

        $questions = $this->container->getQuestions();
        // var_dump($questions);die();
        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('user' => $user)
        );
    }
    
    /**
     * Profil the user
     * @Route("/profil", name="user-profil")
     * @Route("/profil/")
     */
    public function profilAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $questions = $this->container->getQuestions();
        // var_dump($questions);die();

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('user' => $user)
        );
    }
}
