<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use App\Services\StackExchangeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class QuestionsController extends AbstractController
{
    public function __construct(
        private StackExchangeService $stackExchangeService,
        private QuestionRepository $questionRepository
    ) {
    }



    #[Route('/questions', name: 'app_questions')]
    public function index(Request $request): Response
    {
        $term = $request->get('search', null);

        if (!isset($term) || empty($term)) {
            $questions['items'] = $this->questionRepository->get();
        } else {
            $questions = $this->stackExchangeService->search($term);
        }

//        dd( $questions);


        return $this->render('questions/index.html.twig', [
            'questions' => $questions,
            'term' => $term
        ]);
    }
}
