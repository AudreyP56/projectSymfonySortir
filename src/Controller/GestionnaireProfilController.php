<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GestionnaireProfilController extends AbstractController
{
    #[Route('/gestionnaire/profil', name: 'gestionnaire_profil')]
    public function index(Request $request): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        #Appel du répo des Users
        $repoUser = $entityManager->getRepository(User::class);

        #On recupère depuis la base (pour profiter du suivi de Doctrine) l'utilisateur actuel
        $profil = $repoUser->find($this->getUser());

        #Variable qu'on utilisera pour afficher les listes des sites auquel on veut lié l'utilisateur
        $tabVille = [];

        #Ensuite, on recup les sites et on les mets dans le bon format
        $repoSite = $entityManager->getRepository(Site::class);

        $tabSiteTemp = $repoSite->findAll();

        foreach($tabSiteTemp as $value)
        {
            $tabVille[$value->getNom()] = $value->getId();
        }


        #Création du formulaire de modification
        $profilForm = $this->createFormBuilder()
            ->add('pseudo', TextType::class, ['label' => 'Pseudo : ', 'attr' => ['value' => $profil->getPseudo()]])
            ->add('prenom', TextType::class, ['label' => 'Prénom : ', 'attr' => ['value' => $profil->getPrenom()]])
            ->add('nom', TextType::class, ['label' => 'Nom : ', 'attr' => ['value' => $profil->getNom()]])
            ->add('telephone', TextType::class, ['label' => 'Téléphone : ', 'attr' => ['value' => $profil->getTelephone()]])
            ->add('email', TextType::class, ['label' => 'Email : ', 'attr' => ['value' => $profil->getEmail()]])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe : ', 'required' => false ])
            ->add('confirmation', PasswordType::class, ['label' => 'Confirmation : ', 'required' => false ])
            ->add('ville', ChoiceType::class, ['choices' => $tabVille, 'label' => 'Ville de rattachement : ', 'attr' => ['value' => $profil->getSiteId()->getNom()]])
            ->add('photo', ButtonType::class, ['label' => 'Télécharger vers le serveur'])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('reset', ResetType::class, ['label' => 'Annuler'])
        ->getForm();


        $profilForm->handleRequest($request);

        if($profilForm->isSubmitted())
        {
            $data = $profilForm->getData();
            $profil->setNom($data['nom']);
            $profil->setPseudo($data['pseudo']);

            #Ici, on vérifie si le pseudo n'a pas déjà été prit
            if(in_array($profil->getPseudo(),$repoUser->getAllPseudo()))
            {
                return new Response("Ce pseudo est déjà pris");
            }

            $profil->setPrenom($data['prenom']);
            $profil->setTelephone($data['telephone']);
            $profil->setEmail($data['email']);

            #Avant de tester les deux, on vérifie si en gros, l'utilisateur a modifié le mdp ou pas
            #Si les deux sont nuls, il n'a pas cherché à modifier le mot de passe
            #Sinon il a essayé auquel cas on tente la comparaison
            if($data['password'] != null && $data['confirmation'] != null)
            {
                $profil->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));

                #On check si les deux sont égaux ou pas.
                if($profil->getPassword() != password_hash($data['confirmation'], PASSWORD_DEFAULT))
                {
                    return new Response('Le mot de passe et sa confirmation ne sont pas identiques');
                }
            }

            $profil->setSiteId($repoSite->find($data['ville']));

            $entityManager->flush();
        }

        return $this->render('gestionnaire_profil/index.html.twig', [
            'controller_name' => 'GestionnaireProfilController',
            'profilForm' => $profilForm->createView()
        ]);
    }

    #[Route('/gestionnaire/profil/{id}', name: 'gestionnaire_profil_affichage')]
    public function showProfil($id) : Response
    {
        $profil = new User();

        $repoUser = $this->getDoctrine()->getRepository(User::class);
        $profil = $repoUser->find($id);

        return $this->render('gestionnaire_profil/affichageProfil.html.twig', [
            'controller_name' => 'GestionnaireProfilController',
            'profil' => $profil
        ]);
    }
}
