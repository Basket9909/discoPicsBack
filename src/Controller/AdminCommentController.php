<?php

namespace App\Controller;

use App\Entity\Coments;
use App\Entity\Publication;
use App\Repository\ComentsRepository;
use App\Service\StatsService;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminCommentController extends AbstractController
{
    #[Route("/admin/comment/{id}", name : "admin_comments_index")]
    #[IsGranted('ROLE_ADMIN')]
    public function index(ComentsRepository $commentRepo, $id): Response
    {

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $commentRepo->getCommentsForPublication($id)
        ]);
    }

    # Permet de supprimer une publication
    #[Route("/admin/comment/{id}/{idp}/delete", name : "admin_comment_delete")]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Coments $comment, EntityManagerInterface $manager,$idp, TranslatorInterface $translator)
    {

        $message = $translator->trans(('The comment has been deleted'));

        $this->addFlash(
            'success',
            $message
        );

        $manager->remove($comment);
        $manager->flush();

        return $this->redirectToRoute('admin_comments_index', ['id' => $idp, 'withAlert' => true]);
    }
}