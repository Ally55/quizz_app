<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 */
class IndexController
{
    /**
     * @Route("/", name="home")
     */
    public function homePage()
    {
        return new Response('Hello world');
    }
}