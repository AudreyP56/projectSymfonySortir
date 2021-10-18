<?php

namespace App\Controller;

use App\Entity\Sortie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/sortie', name: 'sortie')]
    public function index(): Response
    {
        $sorties = $this->getDoctrine()
            ->getRepository(Sortie::class)->findAll();

        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties
        ]);
    }
}
