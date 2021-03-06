<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom'])

            ->add('prenom', TextType::class, [
                'label' => 'Prenom'])

            ->add('adresse', TextType::class, [
                'label' => 'Adresse'])

            ->add('mail', TextType::class, [
                'label' => 'Mail'])

            ->add('telephone', TextType::class, [
                'label' => 'Telephone'])
                
            ->add('relation', TextType::class, [
                'label' => 'Relation'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
