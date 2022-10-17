<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Publication;
use App\Form\PublicationType;
use App\Service\PaginationService;
use App\Form\PublicationModifyType;
use App\Entity\PublicationImgModify;
use App\Form\AddPublicationImageType;
use App\Form\ImgPublicationModifyType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PublicationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PublicationController extends AbstractController
{

     # Permet d'afficher l'ensemble des publications 
     #[Route("/publications/{page<\d+>?1}", name: "publications_all")]
     public function all(PaginationService $pagination, $page): Response
     {
  
         $pagination->setEntityClass(Publication::class)
                        ->setPage($page)
                        ->setLimit(9);
 
         return $this->render('publication/all.html.twig', [
             'pagination' => $pagination
         ]);
     }


     # Permet d'ajouter une publication
     #[Route("/publication/new", name : "new_publication")]
     #[Security("(is_granted('ROLE_USER') and user.getIsVerified() == true) or is_granted('ROLE_ADMIN')")]
     # param Request $request
     # param EntityManagerInterface $manager
     # return Response
     public function add(Request $request,EntityManagerInterface $manager, TranslatorInterface $translator)
     {
         $publication = new Publication();
         $form = $this->createForm(PublicationType::class, $publication);
         $form->handleRequest($request);
 
         if($form->isSubmitted() && $form->isValid())
         {
             //gestion de mon image
             $file = $form['image']->getData();
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
 
 
             $user = $this->getUser();
             $publication->setUser($user);
 
             $manager->persist($publication);
             $manager->flush();
 
             $message = $translator->trans(('The post has been successfully added'));

             $this->addFlash(
                 'success',
                 $message
             );
 
             return $this->redirectToRoute('publication_show', ['slug' => $publication->getSlug(), 'withAlert' => true]);
         }
 
 
         return $this->render("publication/new.html.twig",[
             'myForm' => $form->createView()
         ]);
     }

    # Permet de modifier une publication
    #[Route("/publication/{slug}/edit", name : "publication_edit")]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    #[Security("(is_granted('ROLE_USER') and user === publication.getUser() ) or is_granted('ROLE_ADMIN')")]
    # @param Request $request
    # @param EntityManagerInterface $manager
    # @param Ad $festival
    # @return Response
    public function edit(Request $request, EntityManagerInterface $manager, Publication $publication, TranslatorInterface $translator)
    {

        $fileName = $publication->getImage();
        if(!empty($fileName)){
            $publication->setImage(
                new File($this->getParameter('uploads_directory').'/'.$publication->getImage())
            );
        }

        $form = $this->createForm(PublicationModifyType::class, $publication);
        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid())
        {
            $publication->setImage($fileName);
            $manager->persist($publication);
            $manager->flush();


            $message = $translator->trans(('The information of the publication has been modified'));

            $this->addFlash(
                'success',
                $message
            );
            return $this->redirectToRoute('publication_show', ['slug' => $publication->getSlug(), 'withAlert' => true]);

        }


        return $this->render("publication/edit.html.twig",[
            "publication" => $publication,
            "myForm" => $form->createView()
        ]);

    }

    # Permet de supprimer une publication
    #[Route("/publication/{slug}/delete", name : "publication_delete")]
    #[Security("(is_granted('ROLE_USER') and user === publication.getUser() ) or is_granted('ROLE_ADMIN')")]
    # @param Request $request
    # @param EntityManagerInterface $manager
    # @return Response
    public function delete(Publication $publication, EntityManagerInterface $manager, TranslatorInterface $translator)
    {

        $message = $translator->trans(('The publication has been deleted'));


        $this->addFlash(
            'success',
            $message
        );

        $manager->remove($publication);
        $manager->flush();

        return $this->redirectToRoute('homepage');
    }

    # Permet d'ajouter une image a une publication
    #[Route("/publication/{slug}/add/img", name : "publication_add_img")]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    # param Request $request
    # @param EntityManagerInterface $manager
    # @return Response
    public function imgAdd(Request $request, EntityManagerInterface $manager, Publication $publication, TranslatorInterface $translator)
    {
        $images = new Images();
        $form = $this->createForm(AddPublicationImageType::class, $images);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $file = $form['url']->getData();
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
                $images->setUrl($newFilename);
                $images->setUser($this->getUser());
                $images->setPublication($publication);
            }

            $manager->persist($images);
            $manager->flush();

            $message = $translator->trans(('The image has been added'));
            
            $this->addFlash(
                'success',
                $message
            );

            return $this->redirectToRoute('publication_show', ['slug' => $publication->getSlug(), 'withAlert' => true]);
        }

        return $this->render("publication/addImage.html.twig",[
            'publication' => $publication,
            'myForm' => $form->createView()
        ]);

    }
    # Permet de supprimer une image a une publication
    #[Route("/publication/{id}/remove/img", name : "publication_remove_img")]
    #[Security("(is_granted('ROLE_USER') and user == images.getUser() ) or is_granted('ROLE_ADMIN')")]
    # param Request $request
    # @param EntityManagerInterface $manager
    # @return Response
    public function imgRemove(Request $request, EntityManagerInterface $manager, Images $images, TranslatorInterface $translator)
    {
        $message = $translator->trans(('The image has been deleted'));


        $this->addFlash(
            'success',
            $message
        );

        $manager->remove($images);
        $manager->flush();

        return $this->redirect($request->headers->get('referer'));
    }


    # Permet de modifier l'image d'une publication
    #[Route("/publication/{slug}/edit/img", name : "publication_edit_img")]
    #[Security("(is_granted('ROLE_USER') and user === publication.getUser() ) or is_granted('ROLE_ADMIN')")]
    # param Request $request
    # @param EntityManagerInterface $manager
    # @return Response
    public function imgModify(Request $request, EntityManagerInterface $manager, Publication $publication, TranslatorInterface $translator)
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

            $message = $translator->trans(('The image of the post has been successfully modified'));
            
            $this->addFlash(
                'success',
                $message
            );

            return $this->redirectToRoute('publication_show', ['slug' => $publication->getSlug(), 'withAlert' => true]);
        }

        return $this->render("publication/imgModify.html.twig",[
            'publication' => $publication,
            'myForm' => $form->createView()
        ]);

    }

   

    # Permet d'afficher toutes les images pour une publication
    #[Route('/publication/img/{slug}', name: 'publication_show_img')]
    # @return Response
    
    public function allImg($slug, Publication $publication)
    {

        return $this->render('publication/allImg.html.twig',[
            'publication' => $publication
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
