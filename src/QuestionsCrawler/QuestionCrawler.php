<?php

namespace App\QuestionsCrawler;

use App\Entity\Answer;
use App\Entity\Question;
use App\Services\PopulateService;
use Doctrine\ORM\EntityManagerInterface;
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

    const CATEGORIES_MAP = [
        'Science: Computers' => 1,
        'Geography' => 2,
        'History' => 3,
        'Entertainment: Books'=> 4,
        'General Knowledge' => 5,
        'Entertainment: Music' => 6,
        'Science: Mathematics' => 7
    ];

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getDataFromAPI(EntityManagerInterface $entityManager)
    {
        $token = $this->getToken();
        $finalResult = [];
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

                $questionsFromRequest = $response['results'];
                $finalResult[$key] =  isset($finalResult[$key]) ? array_merge($questionsFromRequest, $finalResult[$key]) : $questionsFromRequest;

                $maxRequestPerCategory--;
            }
        }
        return $finalResult;
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