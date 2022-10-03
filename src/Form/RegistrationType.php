<?php

namespace App\Form;

use App\Entity\Users;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',TextType::class, $this->getConfiguration("global_input","Prénom","Entrez votre prénom"))
            ->add('lastName',TextType::class, $this->getConfiguration("global_input","Nom","Entrez votre nom"))
            ->add('mail', EmailType::class, $this->getConfiguration("global_input","Email", "Votre adresse email"))
            ->add('password', PasswordType::class, $this->getConfiguration("global_input","Mot de passe", "Votre mot de passe"))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("global_input","Confirmation du mot de passe", "Veuillez confimer votre mot de passe"))
            ->add('bird', DateType::class,[
                'widget' => 'single_text',
                "label" => "Date de naissance",
                "attr" =>[
                    "class" => "global_input"
                ]])
            ->add('instaLink', TextType::class, $this->getConfiguration("global_input","Pseudo instagram","Entrez votre pseudo instagram"))
            ->add('Picture',FileType::class, [
                "label" => "Avatar (jpg , png , gif)",
                "required"=> true,
                "attr" =>[
                    "class" => "global_input"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
