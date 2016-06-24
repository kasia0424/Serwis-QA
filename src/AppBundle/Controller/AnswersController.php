<?php
/**
 * Answers controller class.
 *
 * @copyright (c) 2016 Wanda Sipel
 * @link http://wierzba.wzks.uj.edu.pl/~12_sipel/symfony_projekt/app_dev.php/answers
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Question;
use AppBundle\Form\AnswerType;
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
 * Class AnswersController.
 *
 * @Route(service="app.answers_controller")
 *
 * @package AppBundle\Controller
 * @author Wanda Sipel
 */
class AnswersController
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
     * @var FormFactory $formFactory
     */
    private $formFactory;

    /**
     * AnswersController constructor.
     *
     * @param Translator $translator Translator
     * @param EngineInterface $templating Templating engine
     * @param Session $session Session
     * @param RouterInterface $router
     * @param SecurityContext  $securityContext SecurityContext
     * @param ObjectRepository $questionsModel Model object
     * @param ObjectRepository $answersModel Model object
     * @param ObjectRepository $usersModel Model object
     * @param FormFactory $formFactory Form factory
     */
    public function __construct(
        Translator $translator,
        EngineInterface $templating,
        Session $session,
        RouterInterface $router,
        SecurityContext $securityContext,
        ObjectRepository $questionsModel,
        ObjectRepository $answersModel,
        ObjectRepository $usersModel,
        FormFactory $formFactory
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->session = $session;
        $this->router = $router;
        $this->securityContext = $securityContext;
        $this->questionsModel = $questionsModel;
        $this->answersModel = $answersModel;
        $this->usersModel = $usersModel;
        $this->formFactory = $formFactory;
    }

    /**
     * Index action.
     *
     * @Route("/answers", name="answers")
     * @Route("/answers/")
     *
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function indexAction()
    {
        $answers = $this->answersModel->findAll();
        // if (!$answers) {
            // throw new NotFoundHttpException(
                // $this->translator->trans('messages.not_found')
            // );
        // }
        return $this->templating->renderResponse(
            'AppBundle:Answers:index.html.twig',
            array('data' => $answers)
        );
    }

    /**
     * View action.
     *
     * @Route("/answers/view/{id}", name="answers-view")
     * @Route("/answers/view/{id}/")
     * @ParamConverter("answer", class="AppBundle:Answer")
     *
     * @param Answer $answer Answer entity
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function viewAction(Answer $answer = null)
    {
        $question = $answer->getQuestion();
        if (!$answer) {
            throw new NotFoundHttpException(
                $this->translator->trans('messages.not_found')
            );
        }
        return $this->templating->renderResponse(
            'AppBundle:Answers:view.html.twig',
            array('data' => $answer, 'question' => $question)
        );
    }

    /**
     * Users answers action.
     *
     * @Route("/myanswers", name="user-answers")
     * @Route("/myanswers/")
     *
     * @throws NotFoundHttpException
     * @return Response A Response instance
     */
    public function profileAction(Request $request)
    {
        $answers = $this->answersModel->findAll();
        $user = $this->securityContext->getToken()->getUser();
        $id = $user->getId();
        $myanswers = $this->answersModel->findByUser($user);
        // if (!$myanswers) {
            // throw new NotFoundHttpException(
                // $this->translator->trans('messages.questions_not_found')
            // );
        // }

        return $this->templating->renderResponse(
            'AppBundle:Answers:profile.html.twig',
            array('data' => $myanswers)
        );
    }

    /**
     * Add action.
     *
     * @Route("/questions/{id}/answer-add", name="answers-add")
     * @Route("/questions/{id}/answer-add/")
     * @ParamConverter("question", class="AppBundle:Question")
     *
     * @param question $question question entity
     * @param Request $request
     * @return Response A Response instance
     */
    public function addAction(Request $request, Question $question = null)
    {
        $id = (integer)$request->get('id', null);
        $roleflag = $this->securityContext->isGranted('ROLE_USER');
        if(!$roleflag) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('not.user')
            );
            return new RedirectResponse(
                $this->router->generate('login')
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

        $user = $this->securityContext->getToken()->getUser();
        // $user = $this->usersModel->findById('1');
        $answer = new Answer();
        $answer->setQuestion($question);
        // $answer->setUser($user[0]);
        $answer->setUser($user);
    
        $answerForm = $this->formFactory->create(
            new AnswerType(),
            $answer,
            array(
                'validation_groups' => 'answer-default'
            )
        );

        $answerForm->handleRequest($request);

        if ($answerForm->isValid()) {
            $answer = $answerForm->getData();
            $this->answersModel->save($answer);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('messages.success.add')
            );
            return new RedirectResponse(
                $this->router->generate(
                    'questions-view',
                    array('id' => $id)
                )
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Answers:add.html.twig',
            array('form' => $answerForm->createView())
        );
    }

    /**
     * Edit action.
     *
     * @Route("/answers/edit/{id}", name="answers-edit")
     * @Route("/answers/edit/{id}/")
     * @ParamConverter("answer", class="AppBundle:Answer")
     *
     * @param Answer $answer Answer entity
     * @param Request $request
     * @return Response A Response instance
     */
    // public function editAction(Request $request, Answer $answer = null)
    // {
        // $user = $this->securityContext->getToken()->getUser();
        
        // if (!$answer) {
            // $this->session->getFlashBag()->set(
                // 'warning',
                // $this->translator->trans('messages.not_found')
            // );
            // return new RedirectResponse(
                // $this->router->generate('answers-add')
            // );
        // }

        // $answerForm = $this->formFactory->create(
            // new AnswerType(),
            // $answer,
            // array(
                // 'validation_groups' => 'answer-default',
                // 'question_model' => $this->questionsModel
            // )
        // );

        // $answerForm->handleRequest($request);

        // if ($answerForm->isValid()) {
            // $answer = $answerForm->getData();
            // $this->answersModel->save($answer);
            // $this->session->getFlashBag()->set(
                // 'success',
                // $this->translator->trans('messages.success.edit')
            // );
            // return new RedirectResponse(
                // $this->router->generate('answers')
            // );
        // }

        // return $this->templating->renderResponse(
            // 'AppBundle:Answers:edit.html.twig',
            // array('form' => $answerForm->createView())
        // );

    // }

    /**
     * Delete action.
     *
     * @Route("/answers/delete/{id}", name="answers-delete")
     * @Route("/answers/delete/{id}/")
     * @ParamConverter("answer", class="AppBundle:Answer")
     *
     * @param Answer $answer Answer entity
     * @param Request $request
     * @return Response A Response instance
     */
    public function deleteAction(Request $request, Answer $answer = null)
    {
        $user = $this->securityContext->getToken()->getUser();
        $author = $answer->getUser();
        
        if (!$answer) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('messages.not_found')
            );
            return new RedirectResponse(
                $this->router->generate('answers')
            );
        }

        if($user != $author) {
            $this->session->getFlashBag()->set(
                'warning',
                $this->translator->trans('not.yours')
            );
            return new RedirectResponse(
                $this->router->generate('answers')
            );
        }

        $answerForm = $this->formFactory->create(
            new AnswerType(),
            $answer,
            array(
                'validation_groups' => 'answer-delete'
            )
        );

        $answerForm->handleRequest($request);

        if ($answerForm->isValid()) {
            $answer = $answerForm->getData();
            $this->answersModel->delete($answer);
            $this->session->getFlashBag()->set(
                'success',
                $this->translator->trans('messages.success.delete')
            );
            return new RedirectResponse(
                $this->router->generate('answers')
            );
        }

        return $this->templating->renderResponse(
            'AppBundle:Answers:delete.html.twig',
            array(
                'form' => $answerForm->createView(),
                'answer' => $answer
            )
        );

    }
}
