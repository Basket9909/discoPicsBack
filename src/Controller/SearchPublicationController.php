<?php

namespace App\Controller;

use App\Form\SearchPublicationType;
use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchPublicationController extends AbstractController
{
    # permet de rechercher des festivals
    #[Route('/search', name: 'search_posts')]
    public function index(PublicationRepository $publicationRepository, Request $request): Response
    {

        $publication = $publicationRepository->findAll();
        $form = $this->createForm(SearchPublicationType::class);

        $search = $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $publication = $publicationRepository->search($search->get('words')->getData());
        }
        return $this->render('search/index.html.twig', [
            'publications'=> $publication,
            'myForm' => $form->createView(),
        ]);
    }
}