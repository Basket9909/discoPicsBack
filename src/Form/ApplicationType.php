<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    /**
     * Permet d'avoir la configuration de base d'un champ
     *
     * @param string $class
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    protected function getConfiguration($class,$label,$placeholder,$options=[]){
        return array_merge_recursive([
            'label' => $label,
                'attr' => [
                    'class' => $class,
                    'placeholder'=> $placeholder
                ]
                ],$options);
    }
}