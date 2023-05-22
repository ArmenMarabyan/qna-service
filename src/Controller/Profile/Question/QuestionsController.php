<?php

namespace App\Controller\Profile\Question;

use App\Entity\Question;
use App\Form\QuestionFormType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class QuestionsController extends AbstractController
{

    private const FLASH_LEVEL = 'info';
    private const QUESTION_TYPE = 2; //custom

    public function __construct(
        private EntityManagerInterface $entityManager,
        private QuestionRepository $questionRepository
    ) {
    }

    #[Route('/profile/questions', name: 'profile_questions')]
    public function index(TranslatorInterface $translator): Response
    {
        $user = $this->getUser();

        $questions = $this->questionRepository->findByUser($user->getId());

        return $this->render('profile/question/index.html.twig', [
            'contentTitle' => $translator->trans('Questions'),
            'questions' => $questions
        ]);
    }

    #[Route('/profile/questions/create', name: 'profile_questions_create', methods: ['GET', 'POST'])]
    public function create(Request $request, TranslatorInterface $translator): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionFormType::class, $question);
        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setOwnerId($user->getId());
            $question->setIsAnswered(false);
            $question->setViewCount(0);
            $question->setAnswerCount(0);
            $question->setQuestionType(self::QUESTION_TYPE);

            $this->entityManager->persist($question);
            $this->entityManager->flush();

            $this->addFlash(self::FLASH_LEVEL, 'Added question');

            return $this->redirectToRoute('profile_questions');
        }

        return $this->render('profile/question/form.html.twig', [
            'contentTitle' => $translator->trans('Create question'),
            'form' => $form
        ]);
    }

    #[Route('/profile/questions/update/{id<\d+>}', name: 'profile_questions_update', methods: ['GET', 'POST'])]
    public function update(Question $question, Request $request, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(QuestionFormType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($question);
            $this->entityManager->flush();

            $this->addFlash(self::FLASH_LEVEL, 'Updated question');

            return $this->redirectToRoute('profile_questions_update', ['id' => $question->getId()]);
        }

        return $this->render('profile/question/form.html.twig', [
            'contentTitle' => $translator->trans('Create question'),
            'form' => $form
        ]);
    }

    #[Route('/profile/questions/delete/{id<\d+>}', name: 'profile_questions_delete', methods: ['POST', 'GET'])]
    public function delete(Question $question): Response
    {
        $this->entityManager->remove($question);
        $this->entityManager->flush();

        return $this->redirectToRoute('profile_questions');
    }
}
