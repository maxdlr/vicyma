<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\Lodging;
use App\Vue\VueObjectMaker;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/lodging', name: 'app_lodging_')]
class UserLodgingController extends AbstractController
{
    /**
     * @throws ReflectionException
     */
    #[Route(path: '/{name}', name: 'show', methods: ['GET'])]
    public function show(
        Lodging $lodging
    ): Response
    {
        $lodging = VueObjectMaker::makeVueObjectOf([$lodging])->get();

        return $this->render('lodging/lodging-show.html.twig', [
            'lodging' => $lodging
        ]);
    }
}
