<?php

namespace App\Form;

use App\Entity\User;
use App\FormConfig as AppFormConfig;
use FormConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formConfig = new AppFormConfig();

         $builder
            ->add(
                'pseudo',
                TextType::class,
                array_merge(
                    $formConfig->textConfig("form-control", "Pseudo",$formConfig->label_attr, "", "2", "50")
                )
            )
       
            ->add(
                'email',
                EmailType::class,
                array_merge(
                    $formConfig->textConfig("form-control", "Adresse email",$formConfig->label_attr, "", "2", "50")
                )
            )
            ->add(
                'fullName',
                TextType::class,
                array_merge(
                    $formConfig->textConfig("form-control", "Nom complet", $formConfig->label_attr, "")
                )
            )
            ->add(
                'plainPassword',
                TextType::class,
                array_merge(
                    $formConfig->textConfig("form-control", "Mot de passe", $formConfig->label_attr, "")
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
