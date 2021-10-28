<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Trait\CallApiAdress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreationLieuController extends AbstractController
{
    use CallApiAdress ;

    #[Route('/creation/lieu', name: 'creation_lieu')]
    public function index(): Response
    {
        $lieuForm = $this->createFormBuilder()
            ->add('nomLieu', TextType::class, ['required' => false])
            ->add('rueLieu', TextType::class, ['required' => false])
            ->getForm();

        return $this->render('creation_lieu/creationLieu.html.twig', [
            'controller_name' => 'CreationLieuController',
            'formLieu' => $lieuForm->createView()
        ]);
    }


    #[Route('sauvegarder/lieu', name: 'sauvegarde_lieu')]
    public function recupDonnee(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $lieu = new Lieu();

        $data = json_decode($request->getContent(), true);

        $resource = fopen('sortie.txt', 'w');

        fwrite($resource, implode("/", $data));

        fclose($resource);

        if(array_key_exists('ville', $data) && $data['ville'] != 0)
        {
            $lieu->setNom($data['nom']);

            $lieu->setRue($data['rue']);

            $idVille = $data['ville'];

            $ville = $entityManager->getRepository(Ville::class)->find($idVille);

            $lieu->setVille($ville);

            // mise à jour lieu
            $result= $this->fetchApi( $lieu,$ville);
            if (!empty($result)){
                $lieu->setLatitude($result[0]);
                $lieu->setLongitude($result[1]);
            }

            $entityManager->persist($lieu);
            $entityManager->flush();

        }
        else
        {
            return new Response("La ville n'a pas été selectionnée");
        }

        return $this->redirectToRoute('sorties');
    }
}
