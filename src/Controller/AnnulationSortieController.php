<?php

namespace App\Controller;

use App\Entity\Sortie;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnulationSortieController extends AbstractController
{
    #[Route('/sorties/annulation/sortie/{id}', name: 'annulation_sortie')]
    public function index($id, EntityManagerInterface $entityManager, Request $request ): Response
    {
        //J'ai besoin de récupérer un ville. Sauf que la sortie ne pointe pas sur une ville mais si un lieu qui pointe sur une ville
        //Donc il me faut récupérer le lieu puis ensuite je peux récupérer la ville
        $currentSortie = $entityManager->getRepository(Sortie::class)->find($id);

        $lieu = $currentSortie->getLieuId();

        $ville = $lieu->getVilleId();

        $annulationSortieForm = $this->createFormBuilder()
            ->add('motif', TextareaType::class, ['label' => 'Motif :'])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('reset', ResetType::class, ['label' => 'Annuler'])
            ->getForm();


        $annulationSortieForm->handleRequest($request);
        if($annulationSortieForm->isSubmitted())
        {
            $data = $annulationSortieForm->getData();
            #Il faudra modifier l'état de la sortie (la passé en annuler)
            #Il faudra aussi trouver ou stocker le motif d'annulation
            $currentSortie->setEtat();
        }



        return $this->render('annulation_sortie/index.html.twig', [
            'controller_name' => 'AnnulationSortieController',
            'annulationSortieForm' => $annulationSortieForm->createView(),
            'sortie' => $currentSortie,
            'ville' => $ville,
            'lieu' => $lieu
        ]);
    }
}
