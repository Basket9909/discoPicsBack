<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PublicationImgModify
{
    #[Assert\NotBlank(message : "publication.modify.notBlank")]
    #[Assert\Image(mimeTypes : ["image/png","image/jpeg","image/jpg","image/gif"], mimeTypesMessage : "publication.image.notBlank")]
    #[Assert\File(maxSize : "1024k", maxSizeMessage : "publication.image.big")]
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