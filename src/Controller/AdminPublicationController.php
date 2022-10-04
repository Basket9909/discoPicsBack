<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Service\StatsService;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPublicationController extends AbstractController
{
    #[Route("/admin/publication/{page<\d+>?1}", name : "admin_publications_index")]
    #[IsGranted('ROLE_ADMIN')]
    public function index(PaginationService $pagination, $page, StatsService $statsService): Response
    {
        $publications = $statsService->getPublicationsCount();

        $pagination->setEntityClass(Publication::class)
                   ->setPage($page)
                   ->setLimit(10);

        return $this->render('admin/publication/index.html.twig', [
            'stats' => ['publications'=>$publications],
            'pagination' => $pagination
        ]);
    }

    # Permet de supprimer une publication
    #[Route("/admin/publication/{id}/delete", name : "admin_publication_delete")]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Publication $publication, EntityManagerInterface $manager, TranslatorInterface $translator)
    {

        $message = $translator->trans(('The publication has been deleted'));

        $this->addFlash(
            'success',
            $message
        );

        $manager->remove($publication);
        $manager->flush();

        return $this->redirectToRoute('admin_publications_index');
    }
}