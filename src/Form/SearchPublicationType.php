<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchPublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('words', SearchType::class,[
                'label' =>false,
                'attr' => [
                    'class' => 'global_input',
                    'placeholder' => 'Chercher un spot par nom, ville, pays ou rue'
                ]
            ])
            ->add('search', SubmitType::class,[
                'attr' => [
                    'class' => 'button_connexion'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}