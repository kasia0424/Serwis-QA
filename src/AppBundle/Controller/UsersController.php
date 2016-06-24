<?php
/**
 * Users controller class.
 *
 * @copyright (c) 2016 Agnieszka Gorgolewska
 * @link http://wierzba.wzks.uj.edu.pl/~12_gorgolewska
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use FOS\UserBundle\Security\UserProvider;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Doctrine\ORM\EntityManager;

/**
 * Class UsersController.
 *
 * @Route(service="app.users_controller")
 *
 * @package AppBundle\Controller
 * @author Agnieszka Gorgolewska
 */
class UsersController
{
    /**
     * Translator object.
     *
     * @var Translator $translator
     */
    private $translator;

    /**
     * Template engine.
     *
     * @var EngineInterface $templating
     */
    private $templating;

    /**
     * Session object.
     *
     * @var Session $session
     */
    private $session;

    /**
     * Routing object.
     *
     * @var RouterInterface $router
     */
    private $router;

    /**
     * Model object.
     *
     * @var ObjectRepository $model
     */
    private $model;

    /**
     * Form factory.
     *
     * @var FormFactory $formFactory
     */
    private $formFactory;

    /**
     * @param Translator $translator
     * @param EngineInterface $templating
     * @param Session $session
     * @param RouterInterface $router
     *
     * @param ObjectRepository $model
     * @param FormFactory $formFactory
     */
    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        Session $session,
        RouterInterface $router,
        EntityManager $entityManager,
        ObjectRepository $model,
        FormFactory $formFactory,
        $securityContext,
        $securityEncoder
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->session = $session;
        $this->router = $router;
        $this->em = $entityManager;
        $this->model = $model;
        $this->formFactory = $formFactory;
        $user = null;
        $token = $securityContext->getToken();
        if (null !== $token && is_object($token->getUser())) {
            $this->current_user = $token->getUser();
        } else {
            $this->current_user = null;
        }
        $this->encoder = $securityEncoder;
    }

    /**
     * Index action.
     *
     * @Route("/users", name="users")
     * @Route("/users/")
     *
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function indexAction()
    {
        $users = $this->model->findAll();
        if (!$users) {
            throw new NotFoundHttpException('Users not found!');
        }
        return $this->templating->renderResponse(
            'AppBundle:Users:index.html.twig',
            array('users' => $users)
        );
    }

    /**
     * Add action.
     *
     * @Route("/users/add", name="users-add")
     * @Route("/users/add/")
     *
     * @param Request $request
     * @return Response A Response instance
     */
    public function addAction(Request $request)
    {
       // $userManager = $this->container->get('fos_user.user_manager');

        $userForm = $this->formFactory->create(
            new UserType(),
            null,
            array(
                'validation_groups' => 'user-default'
            )
        );

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user = $userForm->getData();
           // var_dump($user);

            $encoder = $this->encoder->getEncoder($user);
            //$test = $user->getPlainPassword();
            //var_dump($test);

            $password = $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());

            $user->setPassword($password);


            $this->em->persist($user);
            $this->em->flush();
           //$this->model->save($user);
           // $userManager->createUser($user);
            $this->session->getFlashBag()->set(
                'success',
                'messages.success.add'
            );
            return new RedirectResponse(
                $this->router->generate('users')
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Users:add.html.twig',
            array('form' => $userForm->createView())
        );
    }


    /**
     * Edit action.
     *
     * @Route("/users/edit/{id}", name="users-edit")
     * @Route("/users/edit/{id}/")
     *
     * @param Request $request
     * @return Response A Response instance
     */
    public function editAction(Request $request)
    {
        $id = (integer)$request->get('id', null);
        $user = $this->model->findOneById($id);

        if (!$user) {
            throw new NotFoundHttpException('User not found!');
        }
        $userForm = $this->formFactory->create(
            new UserType(),
            $user,
            array(
                'validation_groups' => 'user-default'
            )
        );

        $userForm->handleRequest($request);

        if ($userForm->isValid()) {
            $user = $userForm->getData();
            $this->model->save($user);
            $this->session->getFlashBag()->set(
                'success',
                'messages.success.edit'
            );
            return new RedirectResponse(
                $this->router->generate('users')
            );
        }



        return $this->templating->renderResponse(
            'AppBundle:Users:edit.html.twig',
            array('form' => $userForm->createView())
        );

    }

    /**
     * Delete action.
     *
     * @Route("/users/delete/{id}", name="users-delete")
     * @Route("/users/delete/{id}/")
     *
     * @param Request $request
     * @return Response A Response instance
     */

    public function deleteAction(Request $request)
    {
        $id = (integer)$request->get('id', null);
        $user = $this->model->findOneById($id);

        if (!$user) {
            throw new NotFoundHttpException('User not found!');
        }
        $userForm = $this->formFactory->create(
            new UserType(),
            $user,
            array(
                'validation_groups' => 'user-delete'
            )
        );

        $userForm->handleRequest($request);

        if ($userForm->isValid()) {
            if ($userForm->get('Yes')->isClicked()) {
                $user = $userForm->getData();
                $this->model->delete($user);
                $this->session->getFlashBag()->set(
                    'success',
                    'messages.success.delete'
                );
                return new RedirectResponse(
                    $this->router->generate('users')
                );
            }
        }


        return $this->templating->renderResponse(
            'AppBundle:Users:delete.html.twig',
            array('form' => $userForm->createView(),
                'user' => $user)
        );

    }



    /**
     * View action.
     *
     * @Route("/users/view/{id}", name="users-view")
     * @Route("/users/view/{id}/")
     *
     * @param integer $id Element id
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function viewAction(User $user)
    {
        if (!$user) {
            throw new NotFoundHttpException('User not found!');
        }
        return $this->templating->renderResponse(
            'AppBundle:Users:view.html.twig',
            array('user' => $user)
        );
    }
}
