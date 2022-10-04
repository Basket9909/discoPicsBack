<?php

namespace App\Form;

use App\Entity\Publication;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PublicationModifyType extends ApplicationType
{

    public $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, $this->getConfiguration("global_input",$this->translator->trans('Post name'),$this->translator->trans('Name your post')))
            ->add('country',TextType::class, $this->getConfiguration("global_input",$this->translator->trans('Country'),$this->translator->trans('Enter the name of the country where the photo was taken')))
            ->add('city',TextType::class, $this->getConfiguration("global_input",$this->translator->trans('City'),$this->translator->trans('Enter the name of the city where the photo was taken')))
            ->add('adress',TextType::class, $this->getConfiguration("global_input",$this->translator->trans('Adress'),$this->translator->trans('Enter the rest of the address (street, number,...)')))
            ->add('details',TextType::class, $this->getConfiguration("global_input",$this->translator->trans('Details ( not mandatory )'),$this->translator->trans('Give additional details on the photo'),[
                "required" => false
            ]))
            ->add('tips',TextType::class, $this->getConfiguration("global_input",$this->translator->trans('Tips ( not mandatory )'),$this->translator->trans('Give your tips on this photo'),[
                "required" => false
            ]));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
