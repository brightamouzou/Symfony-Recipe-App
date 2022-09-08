<?php

namespace App\Form;

use App\Entity\Mark;
use App\FormConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formConfig=new FormConfig();
        $builder
            ->add('value', ChoiceType::class, 
                array_merge(
                    $formConfig->numberConfig("form-select",'Noter la recette',$formConfig->label_attr, '', '0', '5'),
                    array(
                        'choices' => ['1'=>1, '2' => 2, '3' => 3, '4' => 4,'5'=>5]
                    )
                )
            )
            ->add('submit', SubmitType::class,[
                'attr'=>[

                    'class'=>'btn btn-primary form-submit',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mark::class,
        ]);
    }
}
