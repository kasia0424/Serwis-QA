<?php
/**
 * Categories controller class.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/categories
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
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

/**
 * Class CategoriesController.
 *
 * @Route(service="app.categories_controller")
 *
 * @package AppBundle\Controller
 * @author Wanda Sipel
 */
class CategoriesController
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
     * CategoriesController constructor.
     *
     * @param Translator $translator Translator
     * @param EngineInterface $templating Templating engine
     * @param Session $session Session
     * @param RouterInterface $router
     * @param SecurityContext  $securityContext SecurityContext
     * @param ObjectRepository $model Model object
     * @param FormFactory $formFactory Form factory
     */
    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        Session $session,
        RouterInterface $router,
        SecurityContext $securityContext,
        ObjectRepository $model,
        FormFactory $formFactory
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->session = $session;
        $this->router = $router;
        $this->securityContext = $securityContext;
        $this->model = $model;
        $this->formFactory = $formFactory;
    }

    /**
     * Index action.
     *
     * @Route("/categories", name="categories")
     * @Route("/categories/")
     *
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function indexAction()
    {
        $categories = $this->model->findAll();
        if (!$categories) {
            throw new NotFoundHttpException(
                $this->translator->trans('messages.not_found')
            );
        }
        return $this->templating->renderResponse(
            'AppBundle:Categories:index.html.twig',
            array('data' => $categories)
        );
    }

    /**
     * View action.
     *
     * @Route("/categories/view/{id}", name="categories-view")
     * @Route("/categories/view/{id}/")
     * @ParamConverter("category", class="AppBundle:category")
     *
     * @param category $category category entity
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function viewAction(category $category = null)
    {
        if (!$category) {
            throw new NotFoundHttpException(
                $this->translator->trans('messages.not_found')
            );
        }
        return $this->templating->renderResponse(
            'AppBundle:Categories:view.html.twig',
            array('data' => $category)
        );
    }

    /**
     * Add action.
     *
     * @Route("/categories/add", name="categories-add")
     * @Route("/categories/add/")
     *
     * @param Request $request
     * @return Response A Response instance
     */
    public function addAction(Request $request)
    {
        $user = $this->securityContext->getToken()->getUser();
        $role = $user->getIsAdmin();
        if (!$role) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('not.admin')
            );
            return new RedirectResponse(
                $this->router->generate('categories')
            );
        }
 
        $categoryForm = $this->formFactory->create(
            new categoryType(),
            null,
            array(
                'validation_groups' => 'category-default'
            )
        );

        $categoryForm->handleRequest($request);

        if ($categoryForm->isValid()) {
            $category = $categoryForm->getData();
            $this->model->save($category);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('messages.success.add')
            );
            return new RedirectResponse(
                $this->router->generate('categories')
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Categories:add.html.twig',
            array('form' => $categoryForm->createView())
        );
    }

    /**
     * Edit action.
     *
     * @Route("/categories/edit/{id}", name="categories-edit")
     * @Route("/categories/edit/{id}/")
     * @ParamConverter("category", class="AppBundle:category")
     *
     * @param $category Category entity
     * @param $request
     * @return Response A Response instance
     */
    public function editAction(Request $request, category $category = null)
    {
        $user = $this->securityContext->getToken()->getUser();
        $role = $user->getIsAdmin();
        if (!$role) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('not.admin')
            );
            return new RedirectResponse(
                $this->router->generate('categories')
            );
        }

        if (!$category) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('messages.not_found')
            );
            return new RedirectResponse(
                $this->router->generate('categories-add')
            );
        }

        $categoryForm = $this->formFactory->create(
            new categoryType(),
            $category,
            array(
                'validation_groups' => 'category-default'
            )
        );

        $categoryForm->handleRequest($request);

        if ($categoryForm->isValid()) {
            $category = $categoryForm->getData();
            $this->model->save($category);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('messages.success.edit')
            );
            return new RedirectResponse(
                $this->router->generate('categories')
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Categories:edit.html.twig',
            array('form' => $categoryForm->createView())
        );

    }

    /**
     * Delete action.
     *
     * @Route("/categories/delete/{id}", name="categories-delete")
     * @Route("/categories/delete/{id}/")
     * @ParamConverter("category", class="AppBundle:category")
     *
     * @param $category Category entity
     * @param $request
     * @return Response A Response instance
     */
    public function deleteAction(Request $request, category $category = null)
    {
        $user = $this->securityContext->getToken()->getUser();
        $role = $user->getIsAdmin();
        if (!$role) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('not.admin')
            );
            return new RedirectResponse(
                $this->router->generate('categories')
            );
        }

        if (!$category) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('messages.not_found')
            );
            return new RedirectResponse(
                $this->router->generate('categories')
            );
        }

        $categoryForm = $this->formFactory->create(
            new categoryType(),
            $category,
            array(
                'validation_groups' => 'category-delete'
            )
        );

        $categoryForm->handleRequest($request);

        if ($categoryForm->isValid()) {
            $category = $categoryForm->getData();
            $this->model->delete($category);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('messages.success.delete')
            );
            return new RedirectResponse(
                $this->router->generate('categories')
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Categories:delete.html.twig',
            array(
                'form' => $categoryForm->createView(),
                'category' => $category
            )
        );

    }
}
