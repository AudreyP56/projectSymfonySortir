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
        $today = new \DateTime("now");
        $addOneMonth = $today->modify( 'first day of next month' );

        $em = $this->entityManager;

        $repo = $em->getRepository(Sortie::class);

         $sorties = $repo->updateStatusSortie();

        $repoEtat = $em->getRepository(Etat::class);
        $etatArchive = $repoEtat->findOneBy( ['label' => Etat::STATUS_ARCHIVE],);
        $etatFerme = $repoEtat->findOneBy( ['label' => Etat::STATUS_FERME],);

        $nbSortieFermée = 0;
        $nbSortieArchivée = 0;

        foreach ($sorties as $sortie){
            if ($sortie->getDateHeureSortie() > $addOneMonth ){
                $sortie->setEtat($etatArchive);
                $nbSortieArchivée += 1;
            }elseif ($sortie->getEtat() != $etatFerme){
                $sortie->setEtat($etatFerme);
                $nbSortieFermée +=1;
            }

            $em->persist($sortie);
            $em->flush();

        }

        $output->writeln("A été fermée ".$nbSortieFermée . " sortie(s) et ".$nbSortieArchivée . " ont été archivée");
        return Command::SUCCESS;
    }
}
