<?php

namespace App\Controller;

use App\Entity\Publication;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicationController extends AbstractController
{
    #[Route('/publications', name: 'all_publications')]
    public function index(): Response
    {
        return $this->render('publication/index.html.twig', [
            'controller_name' => 'SpotsController',
        ]);
    }

    # Permet d'afficher une seule publication
    #[Route('/publication/{slug}', name: 'publication_show')]
    # @return Response
    
    public function show($slug, Publication $publication)
    {

        // $repo = $this->getDoctrine()->getRepository(Festival::class);
        // $festival = $repo->findBy($slug);

        return $this->render('publication/show.html.twig',[
            'publication' => $publication
        ]);
    }
}
