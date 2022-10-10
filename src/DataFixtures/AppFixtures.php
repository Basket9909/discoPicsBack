<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Users;
use App\Entity\Images;
use App\Entity\Coments;
use Cocur\Slugify\Slugify;
use App\Entity\Publication;
use App\Entity\Rating;
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
        $picture = 'https://xsgames.co/randomusers/avatar.php?g=pixel';
        $boites = ['gmail','hotmail','yahoo'];
        $mailExt = ['.be','.com','.fr','.org'];
        $picsum = 'https://picsum.photos/500/500';

        //gestion de l'admin

        $admin = new Users();

        $admin->setFirstName('Romeo')
              ->setLastName('Wilmart')
              ->setMail('romeo.wilmart@hotmail.fr')
              ->setPassword($this->passwordHasher->hashPassword($admin,'passwordAdmin'))
              ->setBird($faker->dateTime())
              ->setInstaLink('romeoo09')
              ->setPicture($picture)
              ->setslug('romeow'.(001))
              ->setRoles(['ROLE_ADMIN'])
              ->setIsVerified(true);
        
              $manager->persist($admin);


        $users = [];
        $publications = [];

        //gestion des users
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
                  ->setInstaLink('romeoo09')
                  ->setPicture('https://xsgames.co/randomusers/avatar.php?g=pixel')
                  ->setSlug($slugify->slugify($firstName.$lastName.rand(1,1000)))
                  ->setRoles(['ROLE_USER']);

            $manager->persist($user);
            $users[] = $user;
        }

        for ($p=1; $p<=20; $p++){
            $publication = new Publication();
            $name = $faker->sentence($nbWords = 5, $variableNbWords = true);
            $city = $faker->city();
            $country = $faker->country();

            $publication->setName($name)
                        ->setCity($city)
                        ->setCountry($country)
                        ->setImage('https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg')
                        ->setAdress($faker->streetName().' '.rand(1,99).','.$faker->postcode().' '.$city.' '.$country)
                        ->setdetails('<p>'.join('</p><p>',$faker->paragraphs(3)).'</p>')
                        ->setTips('<p>'.join('</p><p>',$faker->paragraphs(2)).'</p>')
                        ->setUser($users[rand(0,count($users)-1)])
                        ->setSlug( $slugify->slugify($name.rand(1,10000)));

            $manager->persist($publication);
            $publications[] = $publication;

            for($i=1; $i<=5; $i++){
                $images = new Images();
                $images->setCaption($faker->sentence($nbWords = 5, $variableNbWords = true))
                       ->setUrl('https://picsum.photos/500/500')
                       ->setPublication($publication)
                       ->setUser($users[rand(0,count($users)-1)]);

                $manager->persist($images);
            }

            for($c=1; $c<=10; $c++)
            {
                $comments = new Coments();
                $comments->setDate($faker->dateTime())
                         ->setComment($faker->paragraph())
                         ->setUser($users[rand(0,count($users)-1)])
                         ->setPublication($publication);
                $manager->persist($comments);
            }

            for($r=1; $r<=10; $r++)
            {
                $rating = new Rating();
                $rating->setRate(rand(1,5))
                       ->setUser($users[rand(0,count($users)-1)])
                       ->setPublication($publication);
                $manager->persist($rating);
            }
        }

        $manager->flush();
    }
}
