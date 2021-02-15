<?php


namespace App\Controller;

use App\Services\PopulateService;
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
    public function indexAction(PopulateService $populateService)
    {
        $populateService->persisting();

        return new Response('Done');
    }

}