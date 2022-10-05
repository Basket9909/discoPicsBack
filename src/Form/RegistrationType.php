<?php

namespace App\Form;

use App\Entity\Users;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends ApplicationType
{

    public $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',TextType::class, $this->getConfiguration("global_input",$this->translator->trans('First name')." *",$this->translator->trans('Enter your first name')))
            ->add('lastName',TextType::class, $this->getConfiguration("global_input",$this->translator->trans('Last name')." *",$this->translator->trans('Enter your last name')))
            ->add('mail', EmailType::class, $this->getConfiguration("global_input",$this->translator->trans('Mail')." *", $this->translator->trans('Enter your mail')))
            ->add('password', PasswordType::class, $this->getConfiguration("global_input",$this->translator->trans('Password')." *", $this->translator->trans('Enter your password')))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("global_input",$this->translator->trans('Password confirmation')." *", $this->translator->trans('Please confirm your password')))
            ->add('bird', DateType::class,[
                'widget' => 'single_text',
                "label" => $this->translator->trans('Date of Birth')." *",
                "attr" =>[
                    "class" => "global_input"
                ]])
            ->add('instaLink', TextType::class, $this->getConfiguration("global_input",$this->translator->trans('Instagram username'),$this->translator->trans('Enter your instagram username'),[
                "required" => false
            ]))
            ->add('Picture',FileType::class, [
                "label" => $this->translator->trans("Choose your Avatar"),
                "label_attr" => ['class' => 'avatar_label'],
                "required"=> false,
                "attr" =>[
                    "class" => "global_input special_button_img"
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
