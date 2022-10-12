<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RulesController extends AbstractController
{
    #[Route('/termsofuse', name: 'terms_of_use')]
    public function termsOfUse(): Response
    {
        return $this->render('rules/termsofuse.html.twig', [
            'controller_name' => 'RulesController',
        ]);
    }

    #[Route('/privacy', name: 'privacy')]
    public function privacy(): Response
    {
        return $this->render('rules/privacy.html.twig', [
            'controller_name' => 'RulesController',
        ]);
    }
}