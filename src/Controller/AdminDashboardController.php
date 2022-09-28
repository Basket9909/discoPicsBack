<?php

namespace App\Controller;
                
use App\Service\StatsService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    
    #[Route("/admin", name : "admin_dashboard_index")]
    #[Route('ROLE_ADMIN')]
    public function index(StatsService $statsService): Response
    {
        $users = $statsService->getUsersCount();
        $publications = $statsService->getPublicationsCount();
        
        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => [
                'users' => $users,
                'publications' => $publications,
            ]
        ]);
        
    }
}