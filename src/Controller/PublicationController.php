<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use App\Form\PublicationModifyType;
use App\Entity\PublicationImgModify;
use App\Form\ImgPublicationModifyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
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
                 "La publication a bien été rajoutée"
             );
 
             return $this->redirectToRoute('homepage');
         }
 
 
         return $this->render("publication/new.html.twig",[
             'myForm' => $form->createView()
         ]);
     }

    # Permet de modifier une publication
    #[Route("/publication/{slug}/edit", name : "publication_edit")]
    # @param Request $request
    # @param EntityManagerInterface $manager
    # @param Ad $festival
    # @return Response
    public function edit(Request $request, EntityManagerInterface $manager, Publication $publication)
    {

        $form = $this->createForm(PublicationModifyType::class, $publication);
        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($publication);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations de la publication a bien été modifiée!"
            );
            return $this->redirectToRoute('publication_show', ['slug' => $publication->getSlug(), 'withAlert' => true]);

        }


        return $this->render("publication/edit.html.twig",[
            "publication" => $publication,
            "myForm" => $form->createView()
        ]);

    }

    # Permet de modifier l'image d'une publication
    #[Route("/publication/{slug}/edit/img", name : "publication_edit_img")]
    # param Request $request
    # @param EntityManagerInterface $manager
    # @return Response
    public function imgModify(Request $request, EntityManagerInterface $manager, Publication $publication)
    {
        $PublicationimgModify = new PublicationImgModify();
        $form = $this->createForm(ImgPublicationModifyType::class, $PublicationimgModify);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // Permet de supprimer l'image dans le dossier 
            if(!empty($publication->getImage()))
            {
                unlink($this->getParameter('uploads_directory').'/'.$publication->getImage());
            }

            $file = $form['newPublicationPicture']->getData();
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

            $manager->persist($publication);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre avatar a bien été modifié'
            );

            return $this->redirectToRoute('publication_show', ['slug' => $publication->getSlug(), 'withAlert' => true]);
        }

        return $this->render("publication/imgModify.html.twig",[
            'publication' => $publication,
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
