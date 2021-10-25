<?php

namespace App\Controller;

use Symfony\Component\Filesystem\Filesystem;
use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Site;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
use App\Form\FileUploadType;



class CsvController extends AbstractController
{
    #[Route('/csv', name: 'csv')]
    public function importCsv(Request $request, FileUploader $file_uploader, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(FileUploadType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['upload_file']->getData();

            if ($file) {
                $file_name = $file_uploader->upload($file);
                if (null !== $file_name) // for example
                {
                    $directory = $file_uploader->getTargetDirectory();
                    $full_path = $directory . '/' . $file_name;
                    // Do what you want with the full path file...
                    // Why not read the content or parse it !!!

                    $handle = fopen($full_path, "r");
                    $data = fgetcsv($handle, 1000, ';');
                    $em = $entityManager;

                    $repoSite = $em->getRepository(Site::class);

                    $filesystem = new Filesystem();
                    $ligne = null;
                    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

                        $site = $repoSite->find($data[0]);
                        if(!empty($site)) {
                            $em->getConnection()->beginTransaction();
                            try {
                                $ligne += 1;
                                $user = new User();
                                $user
                                    ->setSiteId($site)
                                    ->setnom($data[1])
                                    ->setprenom($data[2])
                                    ->setPseudo($data[3])
                                    ->setTelephone($data[4])
                                    ->setEmail($data[5])
                                    ->setPassword($data[6]);

                                $em->persist($user);
                                $em->flush();
                                $em->getConnection()->commit();
                            } catch (Exception $e) {
                                $this->addFlash('error', 'M.... ça a planté à la ligne'. $ligne .' !');
                                $filesystem->remove($full_path);

                                return $this->render('csv/index.html.twig', [
                                    'form' => $form->createView(),
                                ]);
                            }
                        }
                    }
                    $this->addFlash('success', 'Réjouissez vous la vie est belle et la totalitée du fichier à été ajouté avec succès');
                    fclose($handle);
                    $filesystem->remove($full_path);
                } else {
                    $this->addFlash('error', 'désolé nous renconrtons une erreur avec votre fichier !');
                }
            }
        }
        return $this->render('csv/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}