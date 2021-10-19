<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreerSortieController extends AbstractController
{
    #[Route('/creer', name: 'creer')]
    public function index(): Response
    {
        return $this->render('creer_sortie/index.html.twig', [
            'controller_name' => 'CreerSortieController',

        ]);
    }
    #[Route('/creer/sortie', name: 'creer_sortie')]
    public function creation(Request $request): Response{
        $user = $this->getUser();
        $sortie = new Sortie();
        $sortie->setOrganisateur($user);
        //repos
        $repoSite = $this->getDoctrine()->getRepository(Site::class);
        $repoVille = $this->getDoctrine()->getRepository(Ville::class);
        $repoLieu = $this->getDoctrine()->getRepository(Lieu::class);

        $site = $repoSite->find($user->getSiteId());
        $villes = $repoVille->findAll();

        //formulaire
        $creationForm = $this->createFormBuilder($sortie)
            ->add('nom', TextType::class, ['widget' => 'single_text'])
            ->add('dateHeureSortie', DateTimeType::class)
            ->add('dateLimite', DateType::class)
            ->add('nbPlace', NumberType::class)
            ->add('duree', NumberType::class)
            ->add('description', TextType::class)
            ->add('villeOrganisatrice', TextType::class)//$site->getNom()
            ->add('ville', TextType::class)//$villes for getName()
            ->add('lieu', TextType::class)//$lieux for getName() en fonction de la ville selectionné
            ->add('rue', TextType::class)//$rue getRue() en fonction du lieu selectionné
            ->add('codePostal', TextType::class)//$codePostal getCodePostal()
            ->add('latitude', TextType::class)
            ->add('longitude', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('save', SubmitType::class, ['label' => 'Publier'])
            ->getForm();
        return $this->render('creer_sortie/index.html.twig', [
            'controller_name' => 'CreerSortieController',
            'creationForm' => $creationForm->createView(),
        ]);
    }
}
