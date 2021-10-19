<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\Sortie;
use App\Form\SortieFilterType;
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
        $userId = $this->getUser()->getId();

        $searchParam =$request->request->all();

        $sorties = $this->getDoctrine()
            ->getRepository(Sortie::class)->findBySearchValue($searchParam,$userId);
        $sites = $this->getDoctrine()
            ->getRepository(Site::class)->findAll();

        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties,
            'sites'=> $sites,
        ]);
    }

    /**
     * @Route("/afficher", name="afficher")
     */
    public function show(){
        dd('show');
    }

    /**
     * @Route("/désinscrire", name="desister")
     */
    public function unsubscribe(){
        dd('se désinscrire');
    }

    /**
     * @Route("/sinscrire", name="sinscrire")
     */
    public function subscribe(){
        dd("s'inscrire");
    }

    /**
     * @Route("/modifier", name="modifier")
     */
    public function update(){
        dd('modifier');
    }
}
