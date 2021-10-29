<?php

namespace App\Controller\Admin;

use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    const FA_ICON = 'fas fa-list';

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return parent::index();
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ProjectSymfonySortir');
    }

    public function configureMenuItems(): iterable
    {


        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('User');
        yield MenuItem::linkToCrud('UserAdminGestion', self::FA_ICON, User::class);
        yield MenuItem::section('Site');
        yield MenuItem::linkToCrud('SiteAdminGestion', self::FA_ICON, Site::class);
        yield MenuItem::section('Ville');
        yield MenuItem::linkToCrud('VilleAdminGestion', self::FA_ICON, Ville::class);
        yield MenuItem::section('Sortie');
        yield MenuItem::linkToCrud('SortieAdminGestion', self::FA_ICON, Sortie::class);
        yield MenuItem::section('Intégration');
        yield MenuItem::linkToRoute('Intégration CSV', 'fas fa-file-csv', 'csv' );
    }
}
