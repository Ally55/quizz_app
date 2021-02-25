<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function homePage()
    {
        $name = 'ally';
        return $this->render('home.html.twig', [
            'name' => $name
        ]);
    }
}