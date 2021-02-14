<?php

namespace App\QuestionsCrawler;

use App\Entity\Category;
use App\Entity\Question;
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
        'Science: Computers' => 'Computer Science',
        'Geography' => 'Geography',
        'History' => 'History',
        'Entertainment: Books'=> 'Books',
        'General Knowledge' => 'General Knowledge',
        'Entertainment: Music' => 'Music',
        'Science: Mathematics' => 'Mathematics'
    ];

    // DB[NumeleCategorieiDinAPI]
    // $category = categorii -> findByName();
    // $question->setCategoryId($category->getId());

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getDataFromAPI(EntityManagerInterface $entityManager)
    {
        $token = $this->getToken();
        foreach (self::API_ID_CATEGORIES as $key => $value) {
            $maxRequestPerCategory = floor(self::MAX_QUESTIONS_PER_CATEGORY / self::AMOUNT_PER_REQUEST);

            while ($maxRequestPerCategory) {
                $response = $this->client->request(
                    'GET',
                    self::BASE_URL . "api.php?amount=" . self::AMOUNT_PER_REQUEST . "&category=" . self::API_ID_CATEGORIES[$key] . "&token=" . $token
                )->toArray();
                //dd($response);
                if ($response['response_code'] !== 0) {
                    break;
                }

//                $keysFromDB_ID_CATEGORIES = array_keys(self::DB_ID_CATEGORIES);
//                $keysFromAPI_ID_CATEGORIES = array_keys(self::API_ID_CATEGORIES);
//                foreach ($keysFromDB_ID_CATEGORIES as $keyDB) {
//                    //self::API_ID_CATEGORIES[] = self::DB_ID_CATEGORIES[] ;
//                    foreach ($keysFromAPI_ID_CATEGORIES as $keyAPI) {
//                        $keyAPI = $keyDB;
//                        dd($keyAPI);
//                    }
//                }

                foreach (self::DB_ID_CATEGORIES as $key => $value) {
                    //$category = self::DB_ID_CATEGORIES[$key];
                    $category = $entityManager->find(self::DB_ID_CATEGORIES[$key], 1);
                    dd($category);
                }

                dd(self::DB_ID_CATEGORIES);
                foreach ($response['results'] as $question) {
//                    $categoryObj = new Category();
//                    $categoryObj->setName($question['category']);

                    $category = self::CATEGORIES_MAP[$question['category']];


                    $questionObj = new Question();
                    $questionObj->setText($question['question']);
                    $questionObj->setDifficulty($question['difficulty']);
                    $questionObj->setIdCategory($question['']);

//                    dd($questionObj);
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