<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    #[Route('/creation/ville', name: 'creation_ville')]
    public function index(): Response
    {
        $VilleForm = $this->createFormBuilder()
            ->add('nomVille', TextType::class, ['required' => true])
            ->add('code_postal', TextType::class, ['required' => true])
            ->getForm();

        return $this->render('ville/index.html.twig', [
            'controller_name' => 'VilleController',
            'formVille' => $VilleForm->createView()
        ]);
    }

    #[Route('sauvegarder/ville', name: 'sauvegarde_ville')]
    public function recupDonnee(Request $request, EntityManagerInterface $entityManager)
    {
        $ville = new Ville();

        $data = json_decode($request->getContent(), true);

        $resource = fopen('ville.txt', 'w');

        fwrite($resource, implode("/", $data));

        fclose($resource);

            $ville->setCodePostal($data['code_postal']);
            $ville->setNom($data['nom']);
            $entityManager->persist($ville);
            $entityManager->flush();

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("listeville", name="listeville")
     */
    public function listeLieu(VilleRepository $villeRepository){

        $villes = $villeRepository->findAll();
        return $this->json($villes);
    }
}
