<?php

namespace App\Command;

use App\Entity\Etat;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'archive:sortie',
    description: 'Add a short description for your command',
)]
class ArchiveSortieCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        // Update the value of the private entityManager variable through injection
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $em = $this->entityManager;

        $repo = $em->getRepository(Sortie::class);
        $sorties = $repo->archiveOldSortie();

        $repoEtat = $em->getRepository(Etat::class);
        $etat = $repoEtat->findOneBy( ['label' => Etat::STATUS_ARCHIVE],);

        $nbSortieUpdate = 0;
        foreach ($sorties as $sortie){
            $sortie->setEtat($etat);
            $em->persist($sortie);
            $em->flush();
            $nbSortieUpdate += 1;
        }

        $output->writeln("A été archivé ". $nbSortieUpdate . " sorties");
        return Command::SUCCESS;
    }
}
