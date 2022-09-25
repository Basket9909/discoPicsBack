<?php

namespace App\Form;

use App\Entity\Publication;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PublicationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, $this->getConfiguration("global_input","Nom de la photo","Donnez un nom à votre photo"))
            ->add('country',TextType::class, $this->getConfiguration("global_input","Pays","Entrez le nom du pays d'oû la photo à été prise"))
            ->add('city',TextType::class, $this->getConfiguration("global_input","Ville","Entrez le nom de la ville d'oû la photo à été prise"))
            ->add('adress',TextType::class, $this->getConfiguration("global_input","Adresse","Entrez le reste de l'adresse (rue,n°,..."))
            ->add('details',TextType::class, $this->getConfiguration("global_input","Détails","Donnez des détails supplémentaire sur le photo"))
            ->add('tips',TextType::class, $this->getConfiguration("global_input","Tips","Donnez vos tips sur cette photo"))
            ->add('image',FileType::class, [
                "label" => "Ajoutez votre photo (jpg , png , gif)",
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
            'data_class' => Publication::class,
        ]);
    }
}
