<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\PublicationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #permet d'afficher un user
    #[Route('/user/{slug}/', name: 'show_user')]
    public function index(Users $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            
        ]);
    }
}
