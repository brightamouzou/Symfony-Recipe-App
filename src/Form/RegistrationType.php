<?php

namespace App\Form;
use App\Entity\User;
use App\FormConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formConfig=new FormConfig();
        
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
                RepeatedType::class,
                [
                    'type'=>PasswordType::class,
                    'first_options'=>[
                        'label'=>"Mot de passe",
                        'attr'=>[
                            'class'=>"form-control mb-3"
                        ]
                    ],
                    'second_options' => [
                        'label' => "Confirmer le mot de passe",
                        'attr' => [
                            'class' => "form-control"
                        ]
                    ],
                    'invalid_message'=>"Les mots de passe ne correspondents pas. "
                ]
            )
            ->add(
                'submit', 
                SubmitType::class,
                [
                    'attr'=>[
                        "class"=>"btn btn-primary mt-4"
                    ],
                    "label"=>"S'inscrire",
                    
                ]

            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
