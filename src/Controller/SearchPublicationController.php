<?php

namespace App\Controller;

use App\Form\SearchPublicationType;
use App\Repository\PublicationRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchPublicationController extends AbstractController
{
    # permet de rechercher des posts
    #[Route('/search', name: 'search_posts')]
    public function index(PublicationRepository $publication, Request $request, UsersRepository $user): Response
    {   
        $form = $this->createForm(SearchPublicationType::class);

        $search = $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $publication = $publication->search($search->get('words')->getData());
            // $user = $user->search($search->get('words')->getData());
        }
        return $this->render('search/index.html.twig', [
            'publications'=> $publication,
            // 'users'=>$user,
            'myForm' => $form->createView(),
        ]);
    }
}