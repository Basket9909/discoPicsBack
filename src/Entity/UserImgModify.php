<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class UserImgModify
{
    #[Assert\NotBlank(message : "publication.modify.notBlank")]
    #[Assert\Image(mimeTypes : ["image/png","image/jpeg","image/jpg","image/gif"], mimeTypesMessage : "publication.image.notBlank")]
    #[Assert\File(maxSize : "1024k", maxSizeMessage : "publication.image.big")]
    private $newPicture;

    public function getNewPicture()
    {
        return $this->newPicture;
    }

    public function setNewPicture($newPicture)
    {
        $this->newPicture = $newPicture;
        return $this;
    }


}