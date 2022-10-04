<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Form\RatingType;
use App\Entity\Publication;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RatingController extends AbstractController
{
   # Permet d'ajouter une note
   #[Route("/publication/{slug}/rate/new", name : "new_rate")]
   #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
   # param Request $request
   # param EntityManagerInterface $manager
   # return Response
   public function newComment(Publication $publication, Request $request,EntityManagerInterface $manager,TranslatorInterface $translator)
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


   $message = $translator->trans(('The note has been added'));


   $this->addFlash(
       'success',
       $message
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
   #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
//    #[ParamConverter("id", class : "Rating", options : ["id"=> "id"])]
   # @param Rating $rating
   # @param Request $request
   # @param EntityManagerInterface $manager
   # @return Response
   public function edit(Rating $rating, Request $request, EntityManagerInterface $manager, TranslatorInterface $translator)
   {

       $form = $this->createForm(RatingType::class, $rating);
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid())
       {
           $manager->persist($rating);
           $manager->flush();

           $message = $translator->trans(('The note has been modified'));

           $this->addFlash(
               'success',
               $message
           );

           return $this->redirectToRoute('publication_show', ['slug' => $rating->getPublication()->getSlug(), 'withAlert' => true]);
       }

       return $this->render("rating/edit.html.twig",[
           "myForm" => $form->createView()
       ]);
   }
}
