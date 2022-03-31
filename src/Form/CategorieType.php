<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle' )

            ->add('couleur',ChoiceType::class,
            array(
                    'choices' => array(
                        'bleu' => 'bleu' ,
                        'gris' => 'gris',
                        'vert' => 'vert',
                        'rouge' => 'rouge',
                        'jaune' => 'jaune',
                        'blanc' => 'blanc',
                )));
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
