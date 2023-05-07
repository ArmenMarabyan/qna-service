<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class QuestionsController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }



    #[Route('/questions', name: 'app_questions')]
    public function index(Request $request): Response
    {
        $term = $request->get('search', null);
        $url = "https://api.stackexchange.com/2.3/search?pagesize=100&order=desc&sort=activity&intitle={$term}&site=stackoverflow";

        $response = $this->client->request(
            'GET',
            $url
        );

//        $statusCode = $response->getStatusCode();
//        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $questions = $response->toArray();

        return $this->render('questions/index.html.twig', [
            'questions' => $questions,
            'term' => $term
        ]);
    }
}
