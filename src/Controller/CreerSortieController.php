<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreerSortieController extends AbstractController
{
    #[Route('/sortie/creer', name: 'creer_sortie')]
    public function creation(Request $request, EntityManagerInterface $entityManager): Response {

        //user courant
        $user = $this->getUser();
        $userBase = $entityManager->getRepository(User::class)->find($user->getId());
        //repos

        $repoSite = $this->getDoctrine()->getRepository(Site::class);
        $repoVille = $this->getDoctrine()->getRepository(Ville::class);
        $repoLieu = $this->getDoctrine()->getRepository(Lieu::class);
        $repoEtat = $this->getDoctrine()->getRepository(Etat::class);

        $site = $repoSite->find($user->getSite());
        $villes = $repoVille->findAll();


        //formulaire
        $creationForm = $this->createFormBuilder()
            ->add('nom', TextType::class)
            ->add('dateHeureSortie', DateTimeType::class,['data'=> new \DateTime(), 'widget'=>'single_text'])
            ->add('dateLimite', DateType::class, ['data' => new \DateTime(), 'widget'=>'single_text'])
            ->add('nbPlace', NumberType::class)
            ->add('duree', NumberType::class)
            ->add('description', TextareaType::class)
            ->add('lieu', TextType::class)
            ->add('rue', TextType::class)//$rue getRue() en fonction du lieu selectionné
            ->add('codePostal', TextType::class)//$codePostal getCodePostal()
            ->add('latitude', TextType::class)
            ->add('longitude', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('saveEtPublier', SubmitType::class, ['label' => 'Publier'])
            ->getForm();
        $creationForm->handleRequest($request);


        if($creationForm->isSubmitted()){

            $data = $creationForm->getData();


            if($data["dateLimite"] <= $data["dateHeureSortie"]){
                if(($data["lieu"] !== "0") && ($data["lieu"] !== "-- Sélectionner un lieu --") ) {
                    $lieuSelect = $repoLieu->findBy(["nom" => $data["lieu"]]);
                    $lieu = $lieuSelect[0];
                    $sortie = new Sortie();
                    $sortie->setOrganisateur($userBase);
                    $sortie->setNom($data["nom"]);
                    $sortie->setDateHeureSortie($data["dateHeureSortie"]);
                    $sortie->setDateLimite($data["dateLimite"]);
                    $sortie->setNbPlace($data["nbPlace"]);
                    $sortie->setDuree($data["duree"]);
                    $sortie->setDescription($data["description"]);
                    $sortie->setLieuId($lieu);// a verifier

                    if ($creationForm->get('save')->isClicked()) {
                        $etatCreation = $repoEtat->findBy(['label' => Etat::STATUS_EN_CREATION]);
                        $sortie->setEtat($etatCreation[0]);
                    }
                    if ($creationForm->get('saveEtPublier')->isClicked()) {
                        $etatOuverte = $repoEtat->findBy(['label' => Etat::STATUS_OUVERTE]);
                        $sortie->setEtat($etatOuverte[0]);
                    }
                    $entityManager->persist($sortie);
                    $entityManager->flush();

                    return $this->redirectToRoute('sorties');
                }
                else{
                    $this->addFlash('error', 'Sélectionner un lieu !');
                }
            }
            else{
                $this->addFlash('error', 'La date limite doit être inférieur à la date de sortie !');
            }

        }

        return $this->render('creer_sortie/index.html.twig', [
            'controller_name' => 'CreerSortieController',
            'creationForm' => $creationForm->createView(),
            'villes' => $villes,
            'site' => $site,
        ]);
    }

    #[Route('/sortie/modifier/{id}', name: 'modifier_sortie')]
    public function modification(Request $request, EntityManagerInterface $entityManager, $id): Response{

        //user courant
        $user = $this->getUser();
        $userBase = $entityManager->getRepository(User::class)->find($user->getId());
        //repos

        $repoSite = $this->getDoctrine()->getRepository(Site::class);
        $repoVille = $this->getDoctrine()->getRepository(Ville::class);
        $repoLieu = $this->getDoctrine()->getRepository(Lieu::class);
        $repoEtat = $this->getDoctrine()->getRepository(Etat::class);
        $sortie = $entityManager->getRepository(Sortie::class)->find($id);
        $lieu = $repoLieu->find($sortie->getLieuId());

        $site = $repoSite->find($user->getSite());
        $villes = $repoVille->findAll();

        $creationForm = $this->createFormBuilder()
            ->add('nom', TextType::class, ['attr'=> ['value'=>$sortie->getNom()]])
            ->add('dateHeureSortie', DateTimeType::class, ['data'=>$sortie->getDateHeureSortie(), 'widget'=>'single_text'])
            ->add('dateLimite', DateType::class, ['data'=>$sortie->getDateLimite(), 'widget'=>'single_text'])
            ->add('nbPlace', NumberType::class, ['attr'=>['value'=>$sortie->getNbPlace()]])
            ->add('duree', NumberType::class, ['attr'=>['value'=>$sortie->getDuree()]])
            ->add('description', TextareaType::class, ['data'=>$sortie->getDescription()])
            ->add('lieu', TextType::class, ['attr'=>['value'=>$sortie->getLieuId()]])
            ->add('rue', TextType::class, ['attr'=>['value'=>$lieu->getRue()]])
            ->add('codePostal', TextType::class, ['attr'=>['value'=>$lieu->getVille()->getCodePostal()]])
            ->add('latitude', TextType::class, ['attr'=>['value'=>$lieu->getLatitude()]])
            ->add('longitude', TextType::class, ['attr'=>['value'=>$lieu->getLongitude()]])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('saveEtPublier', SubmitType::class, ['label' => 'Publier'])
            ->add('supprimer', SubmitType::class,['label'=>'Supprimer'])
            ->getForm();
        $creationForm->handleRequest($request);

        if($creationForm->isSubmitted()){
            $data = $creationForm->getData();
            if($data["dateLimite"] <= $data["dateHeureSortie"]) {
                if (($data["lieu"] !== "0") && ($data["lieu"] !== "-- Sélectionner un lieu --")) {
                    $lieuSelect = $repoLieu->findBy(["nom" => $data["lieu"]]);
                    $lieu = $lieuSelect[0];
                    $sortie->setOrganisateur($userBase);
                    $sortie->setNom($data["nom"]);
                    $sortie->setDateHeureSortie($data["dateHeureSortie"]);
                    $sortie->setDateLimite($data["dateLimite"]);
                    $sortie->setNbPlace($data["nbPlace"]);
                    $sortie->setDuree($data["duree"]);
                    $sortie->setDescription($data["description"]);
                    $sortie->setLieuId($lieu);// a verifier

                    if ($creationForm->get('save')->isClicked()) {
                        $etatCreation = $repoEtat->findBy(['label' => Etat::STATUS_EN_CREATION]);
                        $sortie->setEtat($etatCreation[0]);
                    }
                    if ($creationForm->get('saveEtPublier')->isClicked()) {
                        $etatOuverte = $repoEtat->findBy(['label' => Etat::STATUS_OUVERTE]);
                        $sortie->setEtat($etatOuverte[0]);
                    }
                    if ($creationForm->get('supprimer')->isClicked()) {
                        $entityManager->remove($sortie);
                        $entityManager->flush($sortie);
                        return $this->redirectToRoute('sorties');
                    }
                    $entityManager->flush();

                    return $this->redirectToRoute('sorties');
                }
                else{
                    $this->addFlash('error', 'Sélectionner un lieu !');
                }
            }
            else{
                $this->addFlash('error', 'La date limite doit être inférieur à la date de sortie !');
            }
        }
        return $this->render('creer_sortie/index.html.twig', [
            'controller_name' => 'CreerSortieController',
            'creationForm' => $creationForm->createView(),
            'villes' => $villes,
            'site' => $site,
            'lieu' => $lieu,
        ]);
    }

    #[Route('/sortie/afficher/{id}', name: 'afficher_sortie')]
    public function affichage( EntityManagerInterface $entityManager, $id): Response{

        //user courant
        $user = $this->getUser();

        //repos
        $repoSite = $this->getDoctrine()->getRepository(Site::class);
        $repoLieu = $this->getDoctrine()->getRepository(Lieu::class);

        $sortie = $entityManager->getRepository(Sortie::class)->find($id);
        $lieu = $repoLieu->find($sortie->getLieuId());
        $site = $repoSite->find($user->getSite());


        return $this->render('creer_sortie/affichage.html.twig', [
            'controller_name' => 'CreerSortieController',
            'sortie' => $sortie,
            'site' => $site,
            'lieu' => $lieu,

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
