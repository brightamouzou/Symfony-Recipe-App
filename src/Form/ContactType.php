<?php

namespace App\Form;

use App\Entity\Contact;
use App\FormConfig;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formConfig=new FormConfig();
        $builder
            ->add(
                'fullName',
                TextType::class,
                array_merge(
                    $formConfig->textConfig('form-control mb-3', 'Nom complet',$formConfig->label_attr,'','0'),
                  
                )
            )
            ->add(
                'email',
                EmailType::class,
                array_merge(
                    $formConfig->textConfig('form-control mb-3', 'Email', $formConfig->label_attr, '', '0','100'),
                )
            )
            ->add(
                'subject',
                TextType::class,
                $formConfig->textConfig('form-control mb-3', 'Sujet', $formConfig->label_attr, '', '0','50'),

            )
            ->add(
                'message',
                TextareaType::class,
                $formConfig->textConfig('form-control mb-3', 'Message', $formConfig->label_attr, '','0'),

            )
            ->add(
                'submit',
                SubmitType::class, 
                [
                    'attr' => [
                        'class' => 'btn btn-primary ',
                    ],
                    'label' => 'Envoyer l\'email'
                ]
            )->add('captcha', Recaptcha3Type::class, [
            'constraints' => new Recaptcha3(),
            'action_name' => 'contact',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
