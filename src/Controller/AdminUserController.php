<?php

namespace App\Controller;

use App\Entity\Users;
use App\Service\StatsService;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     public function delete(Users $user, EntityManagerInterface $manager, TranslatorInterface $translator)
     {

        $message = $translator->trans(('The user has been deleted'));
        
         $this->addFlash(
             'success',
             $message
         );
 
         $manager->remove($user);
         $manager->flush();
 
         return $this->redirectToRoute('admin_users_index');
     }

     # Permet de changer le rôle d'un user
     #[Route("/admin/user/{id}/new/role", name : "admin_user_new_admin")]
     #[IsGranted('ROLE_ADMIN')]
     public function newRole(Users $user,  EntityManagerInterface $manager, TranslatorInterface $translator){


        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $manager->flush();

        return $this->redirectToRoute('admin_users_index');
     }
}
