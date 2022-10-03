<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/changelocale/{locale}', name: 'change_locale')]
    public function changeLocale($locale, Request $request): Response
    {
        # On stocke la langue donnée dans la session
        $request->getSession()->set('_locale',$locale);

        # On revient à la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
}
