<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PublicationImgModify
{
    #[Assert\NotBlank(message : "Veuillez ajouter une image")]
    #[Assert\Image(mimeTypes : ["image/png","image/jpeg","image/jpg","image/gif"], mimeTypesMessage : "Vous devez upload un fichier jpg, jpeg, png ou gif")]
    #[Assert\File(maxSize : "1024k", maxSizeMessage : "La taille du fichier est trop grande")]
    private $newPublicationPicture;

    public function getNewPublicationPicture()
    {
        return $this->newPublicationPicture;
    }

    public function setNewPublicationPicture($newPublicationPicture)
    {
        $this->newPublicationPicture = $newPublicationPicture;
        return $this;
    }


}