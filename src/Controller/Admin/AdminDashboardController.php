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
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ProjectSymfonySortir');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('User');
        yield MenuItem::linkToCrud('UserAdminGestion', 'fas fa-list', User::class);
        yield MenuItem::section('Site');
        yield MenuItem::linkToCrud('SiteAdminGestion', 'fas fa-list', Site::class);
        yield MenuItem::section('Ville');
        yield MenuItem::linkToCrud('VilleAdminGestion', 'fas fa-list', Ville::class);
        yield MenuItem::section('Sortie');
        yield MenuItem::linkToCrud('SortieAdminGestion', 'fas fa-list', Sortie::class);
    }
}
