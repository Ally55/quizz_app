<?php


namespace App\Controller;

use App\QuestionsCrawler\QuestionCrawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;

class CrawlerController
{
    /**
     * @param HttpClientInterface $client
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @Route("/Questions", name="Questions")
     */
    public function indexAction(QuestionCrawler $crawler)
    {
        $crawler->getDataFromAPI();

        return new Response('hey');
    }
}