<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\CreationSortieType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function creation(Request $request, EntityManagerInterface $entityManager): Response{
        //user courant
        $user = $this->getUser();
        $userBase = $entityManager->getRepository(User::class)->find($user->getId());
        //repos

        $repoSite = $this->getDoctrine()->getRepository(Site::class);
        $repoVille = $this->getDoctrine()->getRepository(Ville::class);
        $repoLieu = $this->getDoctrine()->getRepository(Lieu::class);
        $repoEtat = $this->getDoctrine()->getRepository(Etat::class);

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
            ->add('lieu', TextType::class)
            ->add('rue', TextType::class)//$rue getRue() en fonction du lieu selectionné
            ->add('codePostal', TextType::class)//$codePostal getCodePostal()
            ->add('latitude', TextType::class)
            ->add('longitude', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('saveEtPublier', SubmitType::class, ['label' => 'Publier'])
            ->getForm();
        $creationForm->handleRequest($request);

        if($creationForm->isSubmitted() && $creationForm->isValid()){

            $data = $creationForm->getData();

            $lieu = $repoLieu->findBy(["nom" => $data["lieu"]]);

            $sortie = new Sortie();
            $sortie->setOrganisateur($userBase);
            $sortie->setNom($data["nom"]);
            $sortie->setDateHeureSortie($data["dateHeureSortie"]);
            $sortie->setDateLimite($data["dateLimite"]);
            $sortie->setNbPlace($data["nbPlace"]);
            $sortie->setDuree($data["duree"]);
            $sortie->setDescription($data["description"]);
            $sortie->setLieuId($lieu[0]);// a verifier

            if ($creationForm->get('save')->isClicked() ) {
                $etatCreation = $repoEtat->findBy(['label'=> Etat::STATUS_EN_CREATION]);
                $sortie->setEtat($etatCreation[0]);
            }
            if ($creationForm->get('saveEtPublier')->isClicked() ) {
                $etatOuverte = $repoEtat->findBy(['label'=> Etat::STATUS_OUVERTE]);
                $sortie->setEtat( $etatOuverte[0]);
            }
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('sorties');
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
