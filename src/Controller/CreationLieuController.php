<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreationLieuController extends AbstractController
{
    #[Route('/creation/lieu', name: 'creation_lieu')]
    public function index(): Response
    {
        return $this->render('creation_lieu/creationLieu.html.twig', [
            'controller_name' => 'CreationLieuController',
        ]);
    }
}
