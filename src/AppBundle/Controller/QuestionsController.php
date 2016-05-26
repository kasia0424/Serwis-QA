<?php
/**
 * Questions controller class.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/questions
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
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
     * Form factory.
     *
     * @var FormFactory $formFactory
     */
    private $formFactory;

    /**
     * QuestionsController constructor.
     *
     * @param Translator $translator Translator
     * @param EngineInterface $templating Templating engine
     * @param Session $session Session
     * @param RouterInterface $router
     * @param ObjectRepository $questionsModel Model object
     * @param ObjectRepository $tagsModel Model object
     * @param ObjectRepository $categoriesModel Model object
     * @param ObjectRepository $answersModel Model object
     * @param FormFactory $formFactory Form factory
     */
    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        Session $session,
        RouterInterface $router,
        ObjectRepository $questionsModel,
        ObjectRepository $tagsModel,
        ObjectRepository $categoriesModel,
        ObjectRepository $answersModel,
        FormFactory $formFactory
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->session = $session;
        $this->router = $router;
        $this->questionsModel = $questionsModel;
        $this->tagsModel = $tagsModel;
        $this->categoriesModel = $categoriesModel;
        $this->answersModel = $answersModel;
        $this->formFactory = $formFactory;
    }

    /**
     * Index action.
     *
     * @Route("/questions", name="questions")
     * @Route("/questions/")
     *
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function indexAction()
    {
        $questions = $this->questionsModel->findAll();
        // if (!$questions) {
            // throw new NotFoundHttpException(
                // $this->translator->trans('questions.messages.questions_not_found')
            // );
        // }
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
                $this->translator->trans('questions.messages.question_not_found')
            );
        }
        return $this->templating->renderResponse(
            'AppBundle:Questions:view.html.twig',
            array('data' => $question, 'tags' => $tags, 'category' => $category, 'answers' => $answers)
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
        $questionForm = $this->formFactory->create(
            new questionType(),
            null,
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
                $this->translator->trans('questions.messages.success.add')
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
     * @Route("/questions/edit/{id}", name="questions-edit")
     * @Route("/questions/edit/{id}/")
     * @ParamConverter("question", class="AppBundle:question")
     *
     * @param question $question question entity
     * @param Request $request
     * @return Response A Response instance
     */
    public function editAction(Request $request, question $question = null)
    {
        if (!$question) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('questions.messages.question_not_found')
            );
            return new RedirectResponse(
                $this->router->generate('questions-add')
            );
        }

        $questionForm = $this->formFactory->create(
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
                $this->translator->trans('questions.messages.success.edit')
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
     * @param question $question question entity
     * @param Request $request
     * @return Response A Response instance
     */
    public function deleteAction(Request $request, question $question = null)
    {
        if (!$question) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('questions.messages.question_not_found')
            );
            return new RedirectResponse(
                $this->router->generate('questions')
            );
        }

        $questionForm = $this->formFactory->create(
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
                $this->translator->trans('questions.messages.success.delete')
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