<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class StackExchangeService
{

    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function search($intitle, $pageSize = 100)
    {
        $url = "https://api.stackexchange.com/2.3/search?pagesize={$pageSize}&order=desc&sort=activity&intitle={$intitle}&site=stackoverflow";

        $response = $this->client->request(
            'GET',
            $url
        );

//        $statusCode = $response->getStatusCode();
//        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $questions = $response->toArray();

        return $questions;
    }
}