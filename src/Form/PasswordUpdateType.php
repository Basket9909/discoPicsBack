<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{

    public $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class,$this->getConfiguration("global_input",$this->translator->trans('Old password'),$this->translator->trans('Enter your old password')))
            ->add('newPassword', PasswordType::class,$this->getConfiguration("global_input",$this->translator->trans('New password'),$this->translator->trans('Enter your new password')))
            ->add('confirmPassword', PasswordType::class,$this->getConfiguration("global_input",$this->translator->trans('New password confirmation'),$this->translator->trans('Confirm your new password')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
