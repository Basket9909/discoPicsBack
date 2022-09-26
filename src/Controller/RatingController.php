<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\Rating;
use App\Form\RatingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RatingController extends AbstractController
{
   # Permet d'ajouter une note
   #[Route("/publication/{slug}/rate/new", name : "new_rate")]
   # param Request $request
   # param EntityManagerInterface $manager
   # return Response
   public function newComment(Publication $publication, Request $request,EntityManagerInterface $manager)
   {
   $rating = new Rating();
   $form = $this->createForm(RatingType::class, $rating);
   $form->handleRequest($request);

   if($form->isSubmitted() && $form->isValid())
   {

   $user = $this->getUser();
   $rating->setPublication($publication)
           ->setUser($user);

   $manager->persist($rating);
   $manager->flush();

   $this->addFlash(
       'success',
       "La note à bien été rajouté"
   );

   return $this->redirectToRoute('publication_show', ['slug' => $publication->getSlug(), 'withAlert' => true]);
   }


   return $this->render("rating/new.html.twig",[
       'publication' => $publication,
       'myForm' => $form->createView()
   ]);
   }

   # Permet de modifier une note 
   #[Route("/publication/{id}/rate/edit", name : "rate_edit")]
   # @param Rating $rating
   # @param Request $request
   # @param EntityManagerInterface $manager
   # @return Response
   public function edit(Rating $rating, Request $request, EntityManagerInterface $manager)
   {

       $form = $this->createForm(RatingType::class, $rating);
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid())
       {
           $manager->persist($rating);
           $manager->flush();

           $this->addFlash(
               'success',
               "La note à été modifiée"
           );

           return $this->redirectToRoute('publication_show', ['slug' => $rating->getPublication()->getSlug(), 'withAlert' => true]);
       }

       return $this->render("rating/edit.html.twig",[
           "myForm" => $form->createView()
       ]);
   }
}
