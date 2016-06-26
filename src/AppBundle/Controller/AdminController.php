<?php
/**
 * Admin controller class.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/admin
 */

namespace AppBundle\Controller;

use Doctrine\Common\Persistence\ObjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\Form\Type\ProfileFormType;
use Symfony\Component\Form\Forms;
use AppBundle\Form\UserType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * Class AdminController.
 *
 * @Route(service="app.admin_controller")
 *
 * @package AppBundle\Controller
 * @author Wanda Sipel
 */
class AdminController
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
     * Security context
     *
     * @var SecurityContext
     */
    protected $securityContext;
    
    /**
     * Users model object.
     *
     * @var ObjectRepository $usersModel
     */
    private $usersModel;
    
    /**
     * Form factory.
     *
     * @var FormFactory $formFactory
     */
    private $formFactory;

    /**
     * AdminController constructor.
     *
     * @param Translator $translator Translator
     * @param EngineInterface $templating Templating engine
     * @param Session $session Session
     * @param RouterInterface $router
     * @param SecurityContext  $securityContext SecurityContext
     * @param ObjectRepository $usersModel Model object
     * @param FormFactory $formFactory Form factory
     */
    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        Session $session,
        RouterInterface $router,
        SecurityContext $securityContext,
        ObjectRepository $usersModel,
        FormFactory $formFactory
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->session = $session;
        $this->router = $router;
        $this->securityContext = $securityContext;
        $this->usersModel = $usersModel;
        $this->formFactory = $formFactory;
    }

    /**
     * Index action.
     *
     * @Route("/admin", name="admin")
     * @Route("/admin/")
     *
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function indexAction()
    {
        $users = $this->usersModel->findAll();

        return $this->templating->renderResponse(
            'AppBundle:Users:index.html.twig',
            array('data' => $users)
        );
    }

    /**
     * Edit action.
     *
     * @Route("/admin/user-edit/{id}", name="user-edit")
     * @Route("/admin/user-edit/{id}/")
     *
     * @param Request $request
     * @return Response A Response instance
     */
    public function editAction(Request $request)
    {
        $id = (integer)$request->get('id', null);
        $usertab = $this->usersModel->findUserById($id);
        $user=$usertab[0];

        $userForm = $this->formFactory->create(
            new ProfileFormType(),
            $user,
            array(
                'validation_groups' => 'profile-default'
            )
        );

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('fos_user_success', 'profile.flash.updated');

            return new RedirectResponse($this->getRedirectionUrl($user));
        }

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('form' => $form->createView())
        );
    }

    /**
     * Change role action.
     *
     * @Route("/admin/user-role/{id}", name="user-role")
     * @Route("/admin/user-role/{id}/")
     *
     * @param Request $request
     * @return Response A Response instance
     */
    public function roleAction(Request $request)
    {
        $id = (integer)$request->get('id', null);
        $user = $this->usersModel->findById($id);
        if (!$user) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('messages.not_found')
            );
            return new RedirectResponse(
                $this->router->generate('admin')
            );
        }

        $userForm = $this->formFactory->create(
            new UserType(),
            null,
            array(
                'validation_groups' => 'admin-delete'
            )
        );
        // $form = $formFactory->createBuilder()
            // ->add('User', 'submit')
            // ->add('Admin', 'submit')
            // ->getForm();


        $userForm->handleRequest($request);

        if ($userForm->isValid()) {
            $user = $userForm->getData();
            if ($userForm->get('User')->isClicked()) {
                $user->setIsAdmin('false');
            } else {
                $user->setIsAdmin('true');
            }
            $em = $this->get("doctrine.orm.entity_manager");
            $this->em->persist($user);
            $this->em->flush();
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('messages.success.delete')
            );
            return new RedirectResponse(
                $this->router->generate('admin')
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Users:role.html.twig',
            array(
                'form' => $userForm->createView(),
                'user' => $user
            )
        );

    }
}
