<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getUsersCount(){
        return $this->manager->createQuery("SELECT COUNT(u) FROM App\Entity\Users u")->getSingleScalarResult();
    }

    public function getPublicationsCount(){
        return $this->manager->createQuery("SELECT COUNT(p) FROM App\Entity\Publication p")->getSingleScalarResult();
    }

}
