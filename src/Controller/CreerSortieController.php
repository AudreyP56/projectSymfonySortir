<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\CreationSortieType;
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
    #[Route('/sortie', name: 'sortie')]
    public function index(): Response
    {
        return $this->render('creer_sortie/index.html.twig', [
            'controller_name' => 'CreerSortieController',

        ]);
    }
    #[Route('/sortie/creer', name: 'creer_sortie')]
    public function creation(Request $request): Response{
        //user courant
        $user = $this->getUser();
        //repos
        $repoSite = $this->getDoctrine()->getRepository(Site::class);
        $repoVille = $this->getDoctrine()->getRepository(Ville::class);
        $repoLieu = $this->getDoctrine()->getRepository(Lieu::class);

        $site = $repoSite->find($user->getSiteId());
        $villes = $repoVille->findAll();
        $lieux = $repoLieu->findAll();

        //formulaire
        $sortie = new Sortie();
        $creationForm = $this->createForm(CreationSortieType::class);
//        $creationForm = $this->createFormBuilder()
//            ->add('nom', TextType::class)
//            ->add('dateHeureSortie', DateTimeType::class)
//            ->add('dateLimite', DateType::class)
//            ->add('nbPlace', NumberType::class)
//            ->add('duree', NumberType::class)
//            ->add('description', TextType::class)
//            ->add('siteOrganisateur', TextType::class)
//            ->add('ville', TextType::class)
//            ->add('lieu', TextType::class)//$lieux for getName() en fonction de la ville selectionné
//            ->add('rue', TextType::class)//$rue getRue() en fonction du lieu selectionné
//            ->add('codePostal', TextType::class)//$codePostal getCodePostal()
//            ->add('latitude', TextType::class)
//            ->add('longitude', TextType::class)
//            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
//            ->add('saveEtPublier', SubmitType::class, ['label' => 'Publier'])
//            ->getForm();
        $creationForm->handleRequest($request);
        if($creationForm->isSubmitted() && $creationForm->isValid()){

        }

        return $this->render('creer_sortie/index.html.twig', [
            'controller_name' => 'CreerSortieController',
            'creationForm' => $creationForm->createView(),
            'villes' => $villes,
            'site' => $site,
        ]);
    }
}
