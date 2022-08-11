<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Faker\Factory;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    //gestion du hash password 
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $slugify = new Slugify();
        $picture = 'https://randomuser.me/api/portraits/';
        $boites = ['gmail','hotmail','yahoo'];
        $mailExt = ['.be','.com','.fr','.org'];

        //gestion de l'admin

        $admin = new Users();

        $admin->setFirstName('Romeo')
              ->setLastName('Wilmart')
              ->setMail('romeo.wilmart@hotmail.fr')
              ->setPassword($this->passwordHasher->hashPassword($admin,'passwordAdmin'))
              ->setBird($faker->dateTime())
              ->setInstaLink('https://www.instagram.com/romeoo09/')
              ->setPicture($picture)
              ->setslug('romeow'.(001))
              ->setRole(['ROLE_ADMIN']);
        
              $manager->persist($admin);

        //gestion des users
        $users = [];

        for($u=1; $u<=20; $u++){
            $user = new Users();
            $hash = $this->passwordHasher->hashPassword($user,'password');
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            $user->setFirstName($firstName)
                  ->setLastName($lastName)
                  ->setMail($firstName.'.'.$lastName.'@'.$faker->randomElement($boites).$faker->randomElement($mailExt))
                  ->setPassword($hash)
                  ->setBird($faker->dateTime())
                  ->setInstaLink('https://www.instagram.com/romeoo09/')
                  ->setPicture($picture)
                  ->setSlug($firstName.$lastName.rand(1,1000))
                  ->setRole(['ROLE_USER']);

            $manager->persist($user);
            $users[] = $user;
        }

        $manager->flush();
    }
}
