<?php

namespace App\Controller\Website;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AboutController extends AbstractController
{
    #[Route(path: '/about', name: 'app_about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('about/index.html.twig');
    }
}