<?php

namespace App\Form;
use App\Entity\Ingredient;
use App\FormConfig as AppFormConfig;
use FormConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formConfig=(new AppFormConfig());
        // dd($formConfig);

        $builder
            ->add('name',TextType::class,
                array_merge(
                    $formConfig->textConfig('form-control mb-3',"Nom"),
                    array(
                        'constraints' => [
                            new Assert\Length(['min'=>2, 'max'=>50]),
                            new Assert\NotBlank()
                        ]
                    )
                ),
                
            )
            ->add('price',MoneyType::class,
                array_merge(
                    $formConfig->numberConfig('form-control mb-3','Prix'),
                     array(
                        'constraints' => [
                            new Assert\LessThan(100),
                            new Assert\Positive()
                        ]
                    )
                )
            )->add('submit',SubmitType::class,[
                'attr'=>[
                    'class'=>'btn btn-primary ',
                ],
                'label'=>'Créer mon ingredient'
            ]);
        ;
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
