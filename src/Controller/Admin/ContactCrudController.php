<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;


class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Contact')
        ->setEntityLabelInPlural('Contacts')
        ->setPaginatorPageSize(20)
        ->addFormTheme("@FOSCKEditor/Form/ckeditor_widget.html.twig")
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->hideOnIndex(),
            TextField::new('fullName', "De"),
            TextField::new('subject', "Sujet"),
            TextField::new('email')
                ->setFormTypeOption("disabled", "disabled"),
            TextareaField::new('message')
            ->setFormType(CKEditorType::class)
            ->hideOnIndex(),
            DateTimeField::new('createdAt', "Envoyé le")
                ->hideOnForm()
                ->setFormTypeOption("disabled", "disabled"),
        ];
    }
    
}
