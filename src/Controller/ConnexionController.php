<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\ConnexionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'connexion')]
    public function index(): Response
    {
       $user = new User();
        $connexionForm = $this->createForm(ConnexionFormType::class, $user);
        return $this->render('connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
            'connexionForm' => $connexionForm->createView(),
        ]);
    }
}
