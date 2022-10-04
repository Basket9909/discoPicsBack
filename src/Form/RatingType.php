<?php

namespace App\Form;

use App\Entity\Rating;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class RatingType extends ApplicationType
{

    public $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rate',IntegerType::class, $this->getConfiguration("global_input",$this->translator->trans('Rating out of 5'), $this->translator->trans('Please indicate your rating from 0 to 5'),[
                'attr' => [
                    'step' => 1,
                    'min' => 0,
                    'max' => 5
                ]
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rating::class,
        ]);
    }
}
