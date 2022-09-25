<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PublicationController extends AbstractController
{
    #[Route('/publications', name: 'all_publications')]
    public function index(): Response
    {
        return $this->render('publication/index.html.twig', [
            'controller_name' => 'SpotsController',
        ]);
    }

     # Permet d'ajouter une publication
     #[Route("/publication/new", name : "new_publication")]
     # param Request $request
     # param EntityManagerInterface $manager
     # return Response
     public function register(Request $request,EntityManagerInterface $manager)
     {
         $publication = new Publication();
         $form = $this->createForm(PublicationType::class, $publication);
         $form->handleRequest($request);
 
         if($form->isSubmitted() && $form->isValid())
         {
             //gestion de mon image
             $file = $form['image']->getData();
             dump($file);
             if(!empty($file))
             {
                 $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                 $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',$originalFilename);
                 $newFilename = $safeFilename."-".uniqid().".".$file->guessExtension();
                 try{
                     $file->move(
                         $this->getParameter('uploads_directory'),
                         $newFilename
                     );
                 }catch(FileException $e){
                     return $e->getMessage();
                 }
                 $publication->setImage($newFilename);
             }
 
        //      //gestion des images
        //      foreach($festival->getImages() as $image)
        //      {
        //          $image->setFestival($festival);
        //          $manager->persist($image);
        //      }
        //  //     foreach($festival->getImages() as $image){
        //  //     $image->setFestival($festival);
        //  //     $manager->persist($image);
        //  // }
 
             $user = $this->getUser();
             $publication->setUser($user);
 
             $manager->persist($publication);
             $manager->flush();
 
             $this->addFlash(
                 'success',
                 "Le festival a bien été rajouté"
             );
 
             return $this->redirectToRoute('homepage');
         }
 
 
         return $this->render("publication/new.html.twig",[
             'myForm' => $form->createView()
         ]);
     }

    # Permet d'afficher une seule publication
    #[Route('/publication/{slug}', name: 'publication_show')]
    # @return Response
    
    public function show($slug, Publication $publication)
    {

        return $this->render('publication/show.html.twig',[
            'publication' => $publication
        ]);
    }
}
