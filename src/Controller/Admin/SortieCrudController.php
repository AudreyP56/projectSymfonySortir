<?php

namespace App\Controller\Admin;

use App\Entity\Sortie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SortieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sortie::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('nom'),
            AssociationField::new('organisateur'),
            DateTimeField::new('dateLimite'),
            DateField::new('dateHeureSortie')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDateFormat(DateTimeField::FORMAT_MEDIUM)
            ->setDateTimeFormat(DateTimeField::FORMAT_LONG)
            ->;
    }
}
