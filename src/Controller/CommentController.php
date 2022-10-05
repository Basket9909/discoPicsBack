<?php

namespace App\Controller;

use App\Entity\Coments;
use App\Form\CommentType;
use App\Entity\Publication;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    # Permet d'ajouter un commentaire
    #[Route("/publication/{slug}/comment/new", name : "new_comment")]
    #[Security("(is_granted('ROLE_USER') and user.getIsVerified() == true ) or is_granted('ROLE_ADMIN')")]
    # param Request $request
    # param EntityManagerInterface $manager
    # return Response
    public function newComment(Publication $publication, Request $request,EntityManagerInterface $manager, TranslatorInterface $translator)
    {
    $comment = new Coments();
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {

    $user = $this->getUser();
    $comment->setPublication($publication)
            ->setUser($user);

    $manager->persist($comment);
    $manager->flush();

    $message = $translator->trans(('The comment has been added'));

    $this->addFlash(
        'success',
        $message
    );

    return $this->redirectToRoute('publication_show', ['slug' => $publication->getSlug(), 'withAlert' => true]);
    }


    return $this->render("comment/new.html.twig",[
        'publication' => $publication,
        'myForm' => $form->createView()
    ]);
    }

     # Permet de modifier un commentaire 
     #[Route("/publication/comment/{id}/edit", name : "comment_edit")]
     #[Security("(is_granted('ROLE_USER') and user === comment.getUser() ) or is_granted('ROLE_ADMIN')")]
     # @param Festival $festival
     # @param Coments $comment
     # @param Request $request
     # @param EntityManagerInterface $manager
     # @return Response
     public function edit(Coments $comment, Request $request, EntityManagerInterface $manager, TranslatorInterface $translator)
     {
 
         $form = $this->createForm(CommentType::class, $comment);
         $form->handleRequest($request);
 
         if($form->isSubmitted() && $form->isValid())
         {
             $manager->persist($comment);
             $manager->flush();
 
             $message = $translator->trans(('The comment has been edited'));

             $this->addFlash(
                 'success',
                 $message
             );
 
             return $this->redirectToRoute('publication_show', ['slug' => $comment->getPublication()->getSlug(), 'withAlert' => true]);
         }
 
         return $this->render("comment/edit.html.twig",[
             "comment" => $comment,
             "myForm" => $form->createView()
         ]);
     }

    # Permet de supprimer un commentaire
    #[Route("/publication/comment/{id}/delete", name : "comment_delete")]
    #[Security("(is_granted('ROLE_USER') and user === comment.getUser() ) or is_granted('ROLE_ADMIN')")]
    # @param Coments $comment
    # @param EntityManagerInterface $manager
    # @return Response
    public function delete(Coments $comment, EntityManagerInterface $manager, TranslatorInterface $translator)
    {

        $message = $translator->trans(('The comment has been deleted'));

        $this->addFlash(
            'success',
            $message
        );

        $manager->remove($comment);
        $manager->flush();

        return $this->redirectToRoute('publication_show', ['slug' => $comment->getPublication()->getSlug(), 'withAlert' => true]);
    }
}
