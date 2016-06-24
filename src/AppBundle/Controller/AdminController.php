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
        // if (!$admin) {
            // throw new NotFoundHttpException(
                // $this->translator->trans('messages.not_found')
            // );
        // }
        return $this->templating->renderResponse(
            'AppBundle:Users:index.html.twig',
            array('data' => $users)
        );
    }

    // /**
     // * View action.
     // *
     // * @Route("/admin/view/{id}", name="admin-view")
     // * @Route("/admin/view/{id}/")
     // * @ParamConverter("admin", class="AppBundle:Admin")
     // *
     // * @param Admin $admin Admin entity
     // * @throws NotFoundHttpException
     // * @return Response A Response instance
     // */
    // public function viewAction(Admin $admin = null)
    // {
        // $question = $admin->getQuestion();
        // if (!$admin) {
            // throw new NotFoundHttpException(
                // $this->translator->trans('messages.not_found')
            // );
        // }
        // return $this->templating->renderResponse(
            // 'AppBundle:Admin:view.html.twig',
            // array('data' => $admin, 'question' => $question)
        // );
    // }

    // /**
     // * Users admin action.
     // *
     // * @Route("/myadmin", name="user-admin")
     // * @Route("/myadmin/")
     // *
     // * @throws NotFoundHttpException
     // * @return Response A Response instance
     // */
    // public function profileAction(Request $request)
    // {
        // $admin = $this->adminModel->findAll();
        // $user = $this->securityContext->getToken()->getUser();
        // $id = $user->getId();
        // $myadmin = $this->adminModel->findByUser($user);
        // if (!$myadmin) {
            // throw new NotFoundHttpException(
                // $this->translator->trans('messages.questions_not_found')
            // );
        // }

        // return $this->templating->renderResponse(
            // 'AppBundle:Admin:profile.html.twig',
            // array('data' => $myadmin)
        // );
    // }

    // /**
     // * Add action.
     // *
     // * @Route("/questions/{id}/admin-add", name="admin-add")
     // * @Route("/questions/{id}/admin-add/")
     // * @ParamConverter("question", class="AppBundle:Question")
     // *
     // * @param question $question question entity
     // * @param Request $request
     // * @return Response A Response instance
     // */
    // public function addAction(Request $request, Question $question = null)
    // {
        // $id = (integer)$request->get('id', null);
        // if (!$question) {
            // $this->session->getFlashBag()->set(
                // 'warning',
                // $this->translator->trans('messages.not_found')
            // );
            // return new RedirectResponse(
                // $this->router->generate('questions-add')
            // );
        // }

        // $user = $this->securityContext->getToken()->getUser();
        // // $user = $this->usersModel->findById('1');
        // $admin = new Admin();
        // $admin->setQuestion($question);
        // // $admin->setUser($user[0]);
        // $admin->setUser($user);
    
        // $adminForm = $this->formFactory->create(
            // new AdminType(),
            // $admin,
            // array(
                // 'validation_groups' => 'admin-default'
            // )
        // );

        // $adminForm->handleRequest($request);

        // if ($adminForm->isValid()) {
            // $admin = $adminForm->getData();
            // $this->adminModel->save($admin);
            // $this->session->getFlashBag()->set(
                // 'success',
                // $this->translator->trans('messages.success.add')
            // );
            // return new RedirectResponse(
                // $this->router->generate(
                    // 'questions-view',
                    // array('id' => $id)
                // )
            // );
        // }

        // return $this->templating->renderResponse(
            // 'AppBundle:Admin:add.html.twig',
            // array('form' => $adminForm->createView())
        // );
    // }

    /**
     * Edit action.
     *
     * @Route("/admin/user-edit/{id}", name="user-edit")
     * @Route("/admin/user-edit/{id}/")
     *
     * @param Admin $admin Admin entity
     * @param Request $request
     * @return Response A Response instance
     */
    public function editAction(Request $request)
    {
        $id = (integer)$request->get('id', null);
        $usertab = $this->usersModel->findUserById($id);
        $user=$usertab[0];
        // var_dump($user);die();

        $userForm = $this->formFactory->create(
            new ProfileFormType(),
            $user,
            array(
                'validation_groups' => 'profile-default'
            )
        );
        
        // $user = $this->container->get('security.context')->getToken()->getUser();
        // if (!is_object($user) || !$user instanceof UserInterface) {
            // throw new AccessDeniedException('This user does not have access to this section.');
        // }

        // $form = $this->get('fos_user.profile.form');
        // $formHandler = $this->get('fos_user.profile.form.handler');

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
    // /**
     // * Edit action.
     // *
     // * @Route("/admin/edit/{id}", name="admin-edit")
     // * @Route("/admin/edit/{id}/")
     // * @ParamConverter("admin", class="AppBundle:Admin")
     // *
     // * @param Admin $admin Admin entity
     // * @param Request $request
     // * @return Response A Response instance
     // */
    // public function editAction(Request $request, Admin $admin = null)
    // {
        // if (!$admin) {
            // $this->session->getFlashBag()->set(
                // 'warning',
                // $this->translator->trans('messages.not_found')
            // );
            // return new RedirectResponse(
                // $this->router->generate('admin-add')
            // );
        // }

        // $adminForm = $this->formFactory->create(
            // new AdminType(),
            // $admin,
            // array(
                // 'validation_groups' => 'admin-default',
                // 'question_model' => $this->questionsModel
            // )
        // );

        // $adminForm->handleRequest($request);

        // if ($adminForm->isValid()) {
            // $admin = $adminForm->getData();
            // $this->adminModel->save($admin);
            // $this->session->getFlashBag()->set(
                // 'success',
                // $this->translator->trans('messages.success.edit')
            // );
            // return new RedirectResponse(
                // $this->router->generate('admin')
            // );
        // }

        // return $this->templating->renderResponse(
            // 'AppBundle:Admin:edit.html.twig',
            // array('form' => $adminForm->createView())
        // );

    // }

    // /**
     // * Delete action.
     // *
     // * @Route("/admin/delete/{id}", name="admin-delete")
     // * @Route("/admin/delete/{id}/")
     // * @ParamConverter("admin", class="AppBundle:Admin")
     // *
     // * @param Admin $admin Admin entity
     // * @param Request $request
     // * @return Response A Response instance
     // */
    // public function deleteAction(Request $request, Admin $admin = null)
    // {
        // if (!$admin) {
            // $this->session->getFlashBag()->set(
                // 'warning',
                // $this->translator->trans('messages.not_found')
            // );
            // return new RedirectResponse(
                // $this->router->generate('admin')
            // );
        // }

        // $adminForm = $this->formFactory->create(
            // new AdminType(),
            // $admin,
            // array(
                // 'validation_groups' => 'admin-delete'
            // )
        // );

        // $adminForm->handleRequest($request);

        // if ($adminForm->isValid()) {
            // $admin = $adminForm->getData();
            // $this->adminModel->delete($admin);
            // $this->session->getFlashBag()->set(
                // 'success',
                // $this->translator->trans('messages.success.delete')
            // );
            // return new RedirectResponse(
                // $this->router->generate('admin')
            // );
        // }

        // return $this->templating->renderResponse(
            // 'AppBundle:Admin:delete.html.twig',
            // array(
                // 'form' => $adminForm->createView(),
                // 'admin' => $admin
            // )
        // );

    // }
}
