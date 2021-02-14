<?php


namespace App\Controller;


use App\QuestionsCrawler\QuestionCrawler;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;


class CrawlerController
{
    /**
     * @param HttpClientInterface $client
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @Route("/questions", name="questions")
     */
    public function indexAction(QuestionCrawler $crawler, EntityManagerInterface $entityManager)
    {
        $crawler->getDataFromAPI($entityManager);

        return new Response('hey');
    }

}