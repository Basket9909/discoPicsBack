<?php

namespace App\Controller;

use App\Entity\Users;
use App\Service\StatsService;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    #[Route("/admin/user/{page<\d+>?1}", name : "admin_users_index")]
    #[IsGranted('ROLE_ADMIN')]
    public function index(PaginationService $pagination, $page, StatsService $statsService): Response
    {
        $users = $statsService->getUsersCount();

        $pagination->setEntityClass(Users::class)
                   ->setPage($page)
                   ->setLimit(10);

        return $this->render('admin/user/index.html.twig', [
            'stats' => ['users' => $users],
            'pagination' => $pagination
        ]);
    }


     # Permet de supprimer un user
     #[Route("/admin/user/{id}/delete", name : "admin_user_delete")]
     #[IsGranted('ROLE_ADMIN')]
     public function delete(Users $user, EntityManagerInterface $manager)
     {
         $this->addFlash(
             'success',
             "Le user <strong>{$user->getFullName()}</strong> a bien été supprimé"
         );
 
         $manager->remove($user);
         $manager->flush();
 
         return $this->redirectToRoute('admin_users_index');
     }
}
