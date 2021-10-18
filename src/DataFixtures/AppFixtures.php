<?php

namespace App\DataFixtures;

use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
      //  for ($i = 0; $i < 20; $i++) {
      //      $sortie = new Sortie();
      //      $sortie->setNom('sortie '.$i);
      //      $sortie->setOrganisateur_id($i);
      //      $sortie->setOrganisateur_id($i);


         //   $manager->persist($product);
       // }

        $manager->flush();
    }
}
