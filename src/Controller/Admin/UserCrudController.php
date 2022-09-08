<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInPlural("Utilisateurs")
        ->setEntityLabelInSingular("Utilisateur")
        ->setPageTitle("index","Symf-Receipt | Administration des utilisateurs")
        ->setPaginatorPageSize(10);

    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm(),
            TextField::new('fullName',"Nom complet"),
            TextField::new('pseudo'),
            TextField::new('email')
            ->setFormTypeOption("disabled","disabled"),
            ArrayField::new('roles')
            ->hideOnIndex(),
            DateTimeField::new('createdAt',"Crée le")
            ->hideOnForm()
            ->setFormTypeOption("disabled","disabled"),
        ];
    }
    
}
