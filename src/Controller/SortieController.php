<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\Sortie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sorties", name="sorties")
     */

    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();

        if(!$user->getActif())
        {
            #Essayer de voir si on ne peut pas écrire un message pour l'user
            return $this->redirectToRoute("app_logout", ['erreur' => 'Le compte est inactif']);
        }

        $searchParam =$request->request->all();

        $sorties = $this->getDoctrine()
            ->getRepository(Sortie::class)->findBySearchValue($searchParam,$userId);

        $sites = $this->getDoctrine()
            ->getRepository(Site::class)->findAll();


        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties,
            'sites'=> $sites,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/afficher", name="afficher")
     */
    public function show(){
        dd('show');
    }

    /**
     * @Route("/sinscrire/{id}", name="sinscrire")
     */
    public function subscribe($id)
    {
        $this->toGoOrNot($id, "addParticipant");
        return $this->redirectToRoute('sorties');
    }

    /**
     * @Route("/désinscrire/{id}", name="desister")
     */
    public function unsubscribe($id)
    {
        $this->toGoOrNot($id, "removeParticipant");

        return $this->redirectToRoute('sorties');
    }

    private function toGoOrNot($id, $goOrNot)
    {
        $user = $this->getUser();
        $today = new \DateTime("now");

        $sortie = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortie->find($id);

        if (!$sortie ) {
            throw $this->createNotFoundException(
                'Aucune sortie de trouvée restez chez vous ! '
            );
        }
        if($sortie->getParticipants()->count() >= $sortie->getNbPlace()){
            return   $this->addFlash('error',  "Plus d'inscriptions pour ".$sortie->getNom() ." le nombre de place a été atteint !");
        }
        if ( $sortie->getDateLimite() < $today) {
         return   $this->addFlash('error',  "les inscriptions/désinscription pour ".$sortie->getNom() ." sont closes désolé!");
        }

        $em = $this->getDoctrine()->getManager();
        if($goOrNot == 'removeParticipant'){
            $sortie = $sortie->removeParticipant($user);
            $this->addFlash('success',  "votre désistement de " .$sortie->getNom(). " a bien été prise en compte vous finirez seule avec votre chat ... tant pis");
        }
        if($goOrNot == 'addParticipant'){
            $sortie = $sortie->addParticipant($user);
            $this->addFlash('success',  "votre inscription a bien été prise en compte pour " .$sortie->getNom());
        }
        $em->persist($sortie);
        $em->flush();
    }

    /**
     * @Route("/modifier", name="modifier")
     */
    public function update(){
        dd('modifier');
    }


}
