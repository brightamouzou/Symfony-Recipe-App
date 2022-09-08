<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Config\Framework\FormConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formConfig = new FormConfig();
        
        $builder
            ->add(
            'plainPassword',
                PasswordType::class,
                [
                    'label' => "Mot de passe actuel",
                    'attr' => [
                        'class' => "form-control mb-3"
                    ]
                ]
            )
            ->add(
            'newPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => "Nouveau mot de passe",
                        'attr' => [
                            'class' => "form-control mb-3"
                        ]
                    ],
                    'second_options' => [
                        'label' => "Confirmer le mot de passe",
                        'attr' => [
                            'class' => "form-control"
                        ]
                    ],
                    'invalid_message' => "Les mots de passe ne correspondents pas. "
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'attr' => [
                        "class" => "btn btn-primary mt-4"
                    ],
                    "label" => "Changer de mot de passe",

                ]

            );
    }


}
