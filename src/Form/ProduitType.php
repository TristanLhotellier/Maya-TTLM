<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Libellé'
            ])
            ->add('prix', IntegerType::class, [
                'label' => 'Prix',
                'invalid_message' => 'Nombre attendu'
            ])
            ->add('dateCreation', DateTimeType::class, [
                'label' => 'Date Création',
                'widget' => 'single_text',
            ])
            ->add('categorie', EntityType::class, [
                'label' => 'categorie',
                'class' => Categorie::class,
                'multiple' => false,
                'expanded' => false])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
