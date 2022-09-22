<?php

namespace App\Form;

use App\Entity\Users;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
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
            ->add('bird', DateType::class, $this->getConfiguration("global_input","Date de naissance", "Entrez votre date de naissance"))
            ->add('instaLink', UrlType::class, $this->getConfiguration("global_input","Instagram","Entrez votre lien instagram"))
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
