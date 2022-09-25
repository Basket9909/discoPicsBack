<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
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
            ->add('bird', DateType::class, $this->getConfiguration("global_input","Date de naissance", "Entrez votre date de naissance"))
            ->add('instaLink', UrlType::class, $this->getConfiguration("global_input","Instagram","Entrez votre lien instagram"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
