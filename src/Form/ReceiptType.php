<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Receipt;
use App\FormConfig as AppFormConfig;
use App\Repository\IngredientRepository;
use Doctrine\DBAL\Types\JsonType;
use FormConfig;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ReceiptType extends AbstractType
{

    private TokenStorageInterface $token; 
    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $formConifg=new AppFormConfig();
        $optionalField=' (<small class="text-warning">Optionnel<small>)';
        $builder
            ->add(
                'name',
                TextType::class,
                array_merge(
                    $formConifg->textConfig('form-control','Nom de recette',$formConifg->label_attr,'','2','50')
                )
            )
            ->add(
                'duration',
                NumberType::class,
                array_merge(
                    $formConifg->textConfig('form-control','Durée de la recette (en minutes)',$formConifg->label_attr,'','1','1440')
                )
            )
            ->add(
                'nbPersons',
                NumberType::class,
                array_merge(
                    $formConifg->textConfig('form-control','Nombre personnes de recette', $formConifg->label_attr, '', '1', '50')
                )
            )
            ->add(
                'difficulty',
                RangeType::class,
                array_merge(
                    $formConifg->numberConfig('form-range', 'Niveau de difficulté', $formConifg->label_attr, '', '1', '5')
                )

            )
            ->add(
                'description',
                TextareaType::class,
                array_merge(
                    $formConifg->textConfig('form-range', 'Description ', $formConifg->label_attr),
                    array(
                        'attr'=>[
                            'class' =>'form-control',
                            'col' =>'30',
                            'row' => '10',
                            
                        ]
                    )
                )
            )
            ->add(
                'price',
                NumberType::class,
                array_merge(
                    $formConifg->numberConfig('form-control', 'Prix ', $formConifg->label_attr, '', '1', '1000',"no")
                )
            )
            ->add(
                'imageFile',
                VichImageType::class, [
                'label'=>"Image de la recette",
                "label_attr"=>[
                    'class'=>"form-label mt-4"
                    ]
                ]
            )
            ->add(
            'ingredients',
            EntityType::class,
            [
                'class'=>Ingredient::class,
                'query_builder' => function (IngredientRepository $er) {
                    return $er->createQueryBuilder('u')
                    ->where("u.user = :user")
                    ->orderBy('u.name', 'ASC')
                    ->setParameter("user",$this->token->getToken ()->getUser());
                },
                'expanded'=>true,
                'multiple' => true,
            ])
            ->add(
                'isFavorite',
                CheckboxType::class,
                [
                    "attr"=>[
                        'class'=>'form m-2',
                        'required'=>false,
                        'checked' => true,
                    ],
                    "label" => 'Favori',
                    "label_attr"=>[
                        'class'=>'mt-2 pr-2',
                    ],
                    "constraints"=>[
                        new Assert\NotNull()
                    ]
                ]
            )
            ->add(
                'submit',
                SubmitType::class, 
                [
                    'attr' => [
                        'class' => 'btn btn-primary ',
                    ],
                    'label' => 'Créer mon ingredient'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Receipt::class,
        ]);
    }
}
