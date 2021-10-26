<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        #Appel du répo des Users
        $repoUser = $entityManager->getRepository(User::class);

        #On recupère depuis la base (pour profiter du suivi de Doctrine) l'utilisateur actuel
        $profil = $repoUser->find($this->getUser()->getId());

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
        $profilForm = $this->createFormBuilder(options:['label' => 'truc', 'attr' => ['enctype' => 'multipart/form-data']])
            ->add('pseudo', TextType::class, ['label' => 'Pseudo : ', 'attr' => ['value' => $profil->getPseudo()]])
            ->add('prenom', TextType::class, ['label' => 'Prénom : ', 'attr' => ['value' => $profil->getPrenom()]])
            ->add('nom', TextType::class, ['label' => 'Nom : ', 'attr' => ['value' => $profil->getNom()]])
            ->add('telephone', TextType::class, ['label' => 'Téléphone : ', 'attr' => ['value' => $profil->getTelephone()]])
            ->add('email', TextType::class, ['label' => 'Email : ', 'attr' => ['value' => $profil->getEmail()]])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe : ', 'required' => false ])
            ->add('confirmation', PasswordType::class, ['label' => 'Confirmation : ', 'required' => false ])
            ->add('ville', ChoiceType::class, ['choices' => $tabVille, 'label' => 'Ville de rattachement : ', 'attr' => ['value' => $profil->getSite()->getNom()]])
            ->add('photo', FileType::class, ['label' => 'Télécharger vers le serveur', 'required' => false])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('reset', ResetType::class, ['label' => 'Annuler'])
        ->getForm();



        $profilForm->handleRequest($request);

        if($profilForm->isSubmitted())
        {
            $data = $profilForm->getData();

            $name = $_FILES['form']['name']['photo'];

            $tmpName = $_FILES['form']['tmp_name']['photo'];

            $size = $_FILES['form']['size']['photo'];

            if (isset($tmpName) && $tmpName != "") {

                $temp = $this->traitementPhotoBeforeUpdate($tmpName, $name, $size);

                if(!$temp['res'])
                {
                    return new Response('Il y a eu une erreur lors du traitement de l\'opération');
                }

                #Ici, on supprime l'ancienne photo du dossier upload
                if($profil->getPhoto() != null)
                {
                    unlink('./uploads/'.$profil->getPhoto());
                }
                $profil->setPhoto($temp['fileName']);
            }

            $profil->setNom($data['nom']);

            $tabPseudoTemp = ['pseudo' => $data['pseudo']];

            #Ici, on vérifie si le pseudo n'a pas déjà été prit
            if(in_array($tabPseudoTemp,$repoUser->getAllPseudo()) && $data['pseudo'] != $profil->getPseudo())
            {
                return new Response("Ce pseudo est déjà pris");
            }

            $profil->setPseudo($data['pseudo']);
            $profil->setPrenom($data['prenom']);
            $profil->setTelephone($data['telephone']);
            $profil->setEmail($data['email']);

            #Avant de tester les deux, on vérifie si en gros, l'utilisateur a modifié le mdp ou pas
            #Si les deux sont nuls, il n'a pas cherché à modifier le mot de passe
            #Sinon il a essayé auquel cas on tente la comparaison
            if($data['password'] != null && $data['confirmation'] != null)
            {
                #On check si les deux sont égaux ou pas.
                if($data['password'] != $data['confirmation'])
                {
                    return new Response('Le mot de passe et sa confirmation ne sont pas identiques');
                }

                $profil->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
            }

            $profil->setSite($repoSite->find($data['ville']));

            $entityManager->flush();

            return $this->redirectToRoute('sorties');
        }

        return $this->render('gestionnaire_profil/index.html.twig', [
            'controller_name' => 'GestionnaireProfilController',
            'profilForm' => $profilForm->createView(),
            'profil' => $profil,
            'imageProfilNom' => './../uploads/'.$profil->getPhoto()
        ]);
    }

    #[Route('/gestionnaire/profil/{id}', name: 'gestionnaire_profil_affichage')]
    public function showProfil($id, EntityManagerInterface $entityManager) : Response
    {
        $repoUser = $entityManager->getRepository(User::class);
        $profil = $repoUser->find($id);

        return $this->render('gestionnaire_profil/affichageProfil.html.twig', [
            'controller_name' => 'GestionnaireProfilController',
            'profil' => $profil,
            'imageProfilNom' => './../../uploads/'.$profil->getPhoto()
        ]);
    }


    #On passe en paramètre le nom de la photo
    public function traitementPhotoBeforeUpdate($tmpName, $name, $size)
    {
        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));
        $extensions = ['jpg', 'png', 'jpeg', 'gif'];
        $bool = true;
        $fileName = $tmpName;

        #Element 0 = width; Element 1 = height
        $donnee = getimagesize($tmpName);

        $width = $donnee[0];
        $height = $donnee[1];

        #On vérifie les mesures de l'image
        if($width > 300 || $height > 300)
        {
            $bool = false;
            echo "L'image ne respecte pas le format demandée : 300x300 maximum <br>";
        }

        #J'ai rajouté la condition bool afin que le test (et donc le move_uploaded_file ne se fasse pas alors qu'on veut échoué la requête)
        if(in_array($extension, $extensions) && $bool){

            $uniqueName = uniqid('', true);
            $fileName = $uniqueName.".".$extension;

            move_uploaded_file($tmpName, './uploads/'.$fileName);
        }
        else{
            $bool = false;
            echo "Mauvaise extension";
            //return new Reponse("L'extension de la photo n'est pas bonne");
        }

        return ['fileName' => $fileName, 'res' => $bool];
    }

}
