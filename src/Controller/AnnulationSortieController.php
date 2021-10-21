<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use Doctrine\ORM\Mapping\Driver\RepeatableAttributeCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnulationSortieController extends AbstractController
{
    #[Route('/annulation/sortie/{id}', name: 'annulation_sortie')]
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
            $labelEtat = $currentSortie->getEtat()->getLabel();

            #On vérifie si la sortie a été publiée avant d'essayer de l'annuler
            if($labelEtat != Etat::STATUS_OUVERTE)
            {
                return new Response("La sortie ne peut pas être annulée, elle n'est pas publiée");
            }

            $data = $annulationSortieForm->getData();
            #Il faudra modifier l'état de la sortie (la passé en annuler)
            #Il faudra aussi trouver ou stocker le motif d'annulation
            $etat = $entityManager->getRepository(Etat::class)->findBy(['label' => 'Ferme']);
            $currentSortie->setEtat($etat[0]);

            #$motif contient le motif d'annulation, j'ai décidé de le concaténer à la description
            $motif = $data['motif'];

            $currentSortie->setDescription($currentSortie->getDescription().' '.$motif);

            $entityManager->flush();

            return $this->redirectToRoute('sorties');
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
