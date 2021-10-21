<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\CreationSortieType;
use App\Repository\LieuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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


        //formulaire
        $creationForm = $this->createFormBuilder()
            ->add('nom', TextType::class)
            ->add('dateHeureSortie', DateTimeType::class)
            ->add('dateLimite', DateType::class)
            ->add('nbPlace', NumberType::class)
            ->add('duree', NumberType::class)
            ->add('description', TextType::class)
            ->add('siteOrganisateur', TextType::class)
            ->add('ville', CollectionType::class, ['allow_add'=>true])
            ->add('lieu', CollectionType::class, ['allow_add'=>true])
            ->add('rue', TextType::class)//$rue getRue() en fonction du lieu selectionné
            ->add('codePostal', TextType::class)//$codePostal getCodePostal()
            ->add('latitude', TextType::class)
            ->add('longitude', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('saveEtPublier', SubmitType::class, ['label' => 'Publier'])
            ->add('annuler', SubmitType::class, ['label' => 'Annuler'])
            ->getForm();
        $creationForm->handleRequest($request);
        if ($creationForm->get('annuler')->isClicked() ) {
            return $this->redirectToRoute('sorties');
        }
        if($creationForm->isSubmitted() && $creationForm->isValid()){

            $data = $creationForm->getData();

            $lieu = $repoLieu->findBy($data["lieu"]);

            $sortie = new Sortie();
            $sortie->setOrganisateur($user);
            $sortie->setNom($data["nom"]);
            $sortie->setDateHeureSortie($data["dateHeureSortie"]);
            $sortie->setDateLimite($data["dateLimite"]);
            $sortie->setNbPlace($data["nbPlace"]);
            $sortie->setDuree($data["duree"]);
            $sortie->setDescription($data["description"]);
            $sortie->setLieuId($lieu);// a verifier

            if ($creationForm->get('save')->isClicked() ) {
                $sortie->setEtat();
            }
            if ($creationForm->get('saveEtPublier')->isClicked() ) {
                $sortie->setEtat();
            }
        }

        return $this->render('creer_sortie/index.html.twig', [
            'controller_name' => 'CreerSortieController',
            'creationForm' => $creationForm->createView(),
            'villes' => $villes,
            'site' => $site,
        ]);
    }

    /**
     * @Route("listeLieu/{id}", name="listeLieu")
     */
    public function listeLieu(Ville $ville, LieuRepository $lieuRepository){
        $lieux = $lieuRepository->findBy(["ville"=> $ville]);
        return $this->json($lieux);
    }

    /**
     * @Route("listeRue/{nom}", name="listeRue")
     */
    public function listeRue(Lieu $lieu, LieuRepository $lieuRepository){
        $rue = $lieuRepository->findBy(["nom"=> $lieu->getNom()]);
        return $this->json($rue);
    }
}
