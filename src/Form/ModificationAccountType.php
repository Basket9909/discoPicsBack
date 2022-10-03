<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ModificationAccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("global_input","Prénom","Votre prénom..."))
            ->add('lastName', TextType::class, $this->getConfiguration("global_input","Nom", "Votre nom de famille..."))
            ->add('mail', EmailType::class, $this->getConfiguration("global_input","Email", "Votre adresse e-mail..."))
            ->add('bird', DateType::class, [
                'widget' => 'single_text',
                "label" => "Date de naissance",
                "attr" =>[
                    "class" => "global_input"
                ]])
            ->add('instaLink', TextType::class, $this->getConfiguration("global_input","Pseudo instagram","Entrez votre pseudo instagram"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
