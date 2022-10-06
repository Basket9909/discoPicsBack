<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Publication;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FavoriteController extends AbstractController
{
    #[Route('/favorite/add/{id}', name: 'add_favorite')]
    public function addFavorite(Publication $publication, EntityManagerInterface $manager, Request $request, TranslatorInterface $translator): Response
    {

        $publication->addFavorite($this->getUser());

       
        $manager->persist($publication);
        $manager->flush();

        

        $this->addFlash(
            'success',
            $translator->trans(('The post has been added to your favorites'))
        );


        return $this->redirect($request->headers->get('referer'));
    }


    #[Route('/favorite/remove/{id}', name: 'remove_favorite')]
    public function removeFavorite(Publication $publication, EntityManagerInterface $manager, Request $request, TranslatorInterface $translator): Response
    {

        $publication->removeFavorite($this->getUser());

       
        $manager->persist($publication);
        $manager->flush();

        

        $this->addFlash(
            'success',
            $translator->trans(('The post has been removed from your favorites'))
        );


        return $this->redirect($request->headers->get('referer'));
    }


    #[Route('/favorite/show/{id}', name: 'show_favorite')]
    public function showFavorite(Users $users,$id): Response
    {



        return $this->render('favorite/show.html.twig', [
            'publications'=> $users->getFavoris(),
        ]);
    }


}
