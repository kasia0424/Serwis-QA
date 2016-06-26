<?php
/**
 * Questions controller class.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/questions
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Question;
use AppBundle\Entity\User;
use AppBundle\Form\QuestionType;
use AppBundle\Form\AnswerType;
use Doctrine\Common\Persistence\ObjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

/**
 * Class QuestionsController.
 *
 * @Route(service="app.questions_controller")
 *
 * @package AppBundle\Controller
 * @author Wanda Sipel
 */
class QuestionsController
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
     * Questions model object.
     *
     * @var ObjectRepository $questionsModel
     */
    private $questionsModel;
    
    /**
     * Tags model object.
     *
     * @var ObjectRepository $tagsModel
     */
    private $tagsModel;
    
    /**
     * Categories model object.
     *
     * @var ObjectRepository $categoriesModel
     */
    private $categoriesModel;

    /**
     * Answers model object.
     *
     * @var ObjectRepository $answersModel
     */
    private $answersModel;

    /**
     * Users model object.
     *
     * @var ObjectRepository $usersModel
     */
    private $usersModel;

    /**
     * Form factory.
     *
     * @var FormFactory $q_formFactory
     */
    private $q_formFactory;

    /**
     * QuestionsController constructor.
     *
     * @param Translator $translator Translator
     * @param EngineInterface $templating Templating engine
     * @param Session $session Session
     * @param RouterInterface $router
     * @param SecurityContext  $securityContext SecurityContext
     * @param ObjectRepository $questionsModel Model object
     * @param ObjectRepository $tagsModel Model object
     * @param ObjectRepository $categoriesModel Model object
     * @param ObjectRepository $answersModel Model object
     * @param ObjectRepository $usersModel Model object
     * @param FormFactory $q_formFactory Form factory
     */
    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        Session $session,
        RouterInterface $router,
        SecurityContext $securityContext,
        ObjectRepository $questionsModel,
        ObjectRepository $tagsModel,
        ObjectRepository $categoriesModel,
        ObjectRepository $answersModel,
        ObjectRepository $usersModel,
        FormFactory $q_formFactory
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->session = $session;
        $this->router = $router;
        $this->securityContext = $securityContext;
        $this->questionsModel = $questionsModel;
        $this->tagsModel = $tagsModel;
        $this->categoriesModel = $categoriesModel;
        $this->answersModel = $answersModel;
        $this->usersModel = $usersModel;
        $this->q_formFactory = $q_formFactory;
    }

    /**
     * Index action.
     *
     * @Route("/questions", name="questions")
     * @Route("/questions/")
     *
     * @param Request $request
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function indexAction(Request $request)
    {
        $questions = $this->questionsModel->findAll();
        if (!$questions) {
            throw new NotFoundHttpException(
                $this->translator->trans('messages.questions_not_found')
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Questions:index.html.twig',
            array('data' => $questions)
        );
    }

    /**
     * View action.
     *
     * @Route("/questions/view/{id}", name="questions-view")
     * @Route("/questions/view/{id}/")
     * @ParamConverter("question", class="AppBundle:question")
     *
     * @param question $question question entity
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function viewAction(question $question = null)
    {
        $tags = $question->getTags();
        $category = $question->getCategory();
        $answers = $question->getAnswers();

        if (!$question) {
            throw new NotFoundHttpException(
                $this->translator->trans('messages.not_found')
            );
        }
        return $this->templating->renderResponse(
            'AppBundle:Questions:view.html.twig',
            array('data' => $question, 'tags' => $tags, 'category' => $category, 'answers' => $answers)
        );
    }
    
    /**
     * Users questions action.
     * @param Request $request Request
     *
     * @Route("/myquestions", name="user-questions")
     * @Route("/myquestions/")
     *
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function profileAction(Request $request)
    {
        $questions = $this->questionsModel->findAll();
        $user = $this->securityContext->getToken()->getUser();
        $id = $user->getId();
        $myquestions = $this->questionsModel->findByUser($user);
        if (!$questions) {
            throw new NotFoundHttpException(
                $this->translator->trans('messages.questions_not_found')
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Questions:profile.html.twig',
            array('data' => $myquestions)
        );
    }

    /**
     * Add action.
     *
     * @Route("/questions/add", name="questions-add")
     * @Route("/questions/add/")
     *
     * @param Request $request
     * @return Response A Response instance
     */
    public function addAction(Request $request)
    {
        $roleflag = $this->securityContext->isGranted('ROLE_USER');
        if (!$roleflag) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('not.user')
            );
            return new RedirectResponse(
                $this->router->generate('login')
            );
        }

        $user = $this->securityContext->getToken()->getUser();
        $question = new Question();
        $question->setUser($user);
        
        $questionForm = $this->q_formFactory->create(
            new questionType(),
            $question,
            array(
                'validation_groups' => 'question-default',
                'tag_model' => $this->tagsModel,
                'category_model' => $this->categoriesModel
            )
        );

        $questionForm->handleRequest($request);

        if ($questionForm->isValid()) {
            $question = $questionForm->getData();
            $this->questionsModel->save($question);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('messages.success.add')
            );
            return new RedirectResponse(
                $this->router->generate('questions')
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Questions:add.html.twig',
            array('form' => $questionForm->createView())
        );
    }

    /**
     * Edit action.
     *
     * @param $question question entity
     * @param $request
     *
     * @Route("/questions/edit/{id}", name="questions-edit")
     * @Route("/questions/edit/{id}/")
     * @ParamConverter("question", class="AppBundle:question")
     *
     * @return Response A Response instance
     */
    public function editAction(Request $request, question $question = null)
    {
        $user = $this->securityContext->getToken()->getUser();
        $author = $question->getUser();
        if ($user != $author) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('not.yours')
            );
            return new RedirectResponse(
                $this->router->generate('questions')
            );
        }
        
        if (!$question) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('messages.not_found')
            );
            return new RedirectResponse(
                $this->router->generate('questions-add')
            );
        }

        $questionForm = $this->q_formFactory->create(
            new questionType(),
            $question,
            array(
                'validation_groups' => 'question-edit',
                'tag_model' => $this->tagsModel,
                'category_model' => $this->categoriesModel
            )
        );

        $questionForm->handleRequest($request);

        if ($questionForm->isValid()) {
            $question = $questionForm->getData();
            $this->questionsModel->save($question);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('messages.success.edit')
            );
            return new RedirectResponse(
                $this->router->generate('questions')
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Questions:edit.html.twig',
            array('form' => $questionForm->createView())
        );

    }

    /**
     * Delete action.
     *
     * @Route("/questions/delete/{id}", name="questions-delete")
     * @Route("/questions/delete/{id}/")
     * @ParamConverter("question", class="AppBundle:question")
     *
     * @param $question Question entity
     * @param $request Request
     * @return Response A Response instance
     */
    public function deleteAction(Request $request, question $question = null)
    {
        $user = $this->securityContext->getToken()->getUser();
        $author = $question->getUser();
        if ($user != $author) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('not.yours')
            );
            return new RedirectResponse(
                $this->router->generate('questions')
            );
        }

        if (!$question) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('messages.not_found')
            );
            return new RedirectResponse(
                $this->router->generate('questions')
            );
        }

        $questionForm = $this->q_formFactory->create(
            new questionType(),
            $question,
            array(
                'validation_groups' => 'question-delete',
                'tag_model' => $this->tagsModel,
                'category_model' => $this->categoriesModel
            )
        );

        $questionForm->handleRequest($request);

        if ($questionForm->isValid()) {
            $question = $questionForm->getData();
            $this->questionsModel->delete($question);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('messages.success.delete')
            );
            return new RedirectResponse(
                $this->router->generate('questions')
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Questions:delete.html.twig',
            array(
                'form' => $questionForm->createView(),
                'question' => $question
            )
        );

    }
}
