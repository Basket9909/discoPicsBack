<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(PublicationRepository $publicationrepo): Response
    {
        return $this->render('home.html.twig', [
            'publications' => $publicationrepo->findBestPubli(6),
        ]);
    }
}
