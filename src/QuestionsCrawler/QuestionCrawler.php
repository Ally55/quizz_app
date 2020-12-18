<?php

namespace App\QuestionsCrawler;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class QuestionCrawler
{
    const MAX_QUESTIONS_PER_CATEGORY = 1000;
    const API_ID_CATEGORIES = [
        'Science: Computers' => 18,
        'Geography' => 22,
        'History' => 23,
        'Entertainment: Books'=> 10,
        'General Knowledge' => 11,
        'Entertainment: Music' => 12,
        'Science: Mathematics' => 19
    ];
    const BASE_URL = 'https://opentdb.com/';
    const AMOUNT_PER_REQUEST = 50;

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getDataFromAPI()
    {
        $token = $this->getToken();
        foreach (self::API_ID_CATEGORIES as $key => $value) {
            $maxRequestPerCategory = floor(self::MAX_QUESTIONS_PER_CATEGORY / self::AMOUNT_PER_REQUEST);

            while ($maxRequestPerCategory) {
                $response = $this->client->request(
                    'GET',
                    self::BASE_URL . "api.php?amount=" . self::AMOUNT_PER_REQUEST . "&category=" . self::API_ID_CATEGORIES[$key] . "&token=" . $token
                )->toArray();

                if ($response['response_code'] !== 0) {
                    break;
                }

                $maxRequestPerCategory--;
            }

        }

    }

    private function getToken(): string
    {
        $response = $this->client->request(
            'GET',
            self::BASE_URL . 'api_token.php?command=request'
        );

        return $response->toArray()['token'];
    }
}