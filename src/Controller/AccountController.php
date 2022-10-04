<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ImgModifyType;
use App\Entity\UserImgModify;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use App\Form\ModificationAccountType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PublicationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccountController extends AbstractController
{
    # Permet d'enregistrer un nouveaux user
    #[Route("/registration", name : "account_register")]
    # param Request $request
    # param EntityManagerInterface $manager
    # param UserPasswordHasherInterface $hasher
    # return Response
    public function register(Request $request,EntityManagerInterface $manager,UserPasswordHasherInterface $hasher, TranslatorInterface $translator)
    {
        $user = new Users();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //gestion de mon image
            $file = $form['Picture']->getData();
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
                $user->setPicture($newFilename);
            }

            $hash = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);

            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
            $manager->flush();

            $message = $translator->trans(('You have been successfully registered'));

            $this->addFlash(
                'success',
                $message
            );

            return $this->redirectToRoute('account_login');
        }


        return $this->render("account/new.html.twig",[
            'myForm' => $form->createView()
        ]);
    }

    # Permet d'afficher le profil de l'utilisateur connecté
    #[Route("/account/{id}", name : "account_index")]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
     # @return Response
     public function myAccount(PublicationRepository $publicationRepo,$id )
     {
        
         return $this->render('account/profile.html.twig',[
            'publications'=> $publicationRepo->getPublicationForUserWithMaxResult($id),
            'user' => $this->getUser(),
            
            
         ]);
     }


    # Permet de modifier son profil 
    #[Route("/account/profile/modify", name : "profile_modify")]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    # @param Request $request
    # @param EntityManagerInterface $manager
    # @return Response
    public function profile(Request $request, EntityManagerInterface $manager, TranslatorInterface $translator)
    {
        $user = $this->getUser(); // récup l'utilisateur connecté
        
        // pour la validation des images
        $fileName = $user->getPicture();
        if(!empty($fileName)){
            $user->setPicture(
                new File($this->getParameter('uploads_directory').'/'.$user->getPicture())
            );
        }
        
        $form = $this->createForm(ModificationAccountType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            //gestion image 
            $user->setSlug('')
            ->setPicture($fileName);

            $manager->persist($user);
            $manager->flush();

            $message = $translator->trans(('Data saved successfully'));

            $this->addFlash(
                'success',
                $message
            );

           return $this->redirectToRoute('account_index',['id' => $user->getId(), 'withAlert' => true]);

        }

        return $this->render("account/infosModify.html.twig",[
            'myForm' => $form->createView()
        ]);


    }

    # Permet de modifier le mot de passe
    #[Route("/account/password/modify", name : "password_modify")]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    # @param Request $request
    # @param EntityManagerInterface $manager
    # @param UserPasswordHasherInterface $hasher
    # @return Response
    public function updatePassword(Request $request,EntityManagerInterface $manager,UserPasswordHasherInterface $hasher, TranslatorInterface $translator)
    {
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);

        if($form->isSubmitted() &&  $form->isValid())
        {
            // vérif que le mot de passe correspond à l'ancien
            if(!password_verify($passwordUpdate->getOldPassword(),$user->getPassword()))
            {
                // gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel"));

            }else{
                $newPassword= $passwordUpdate->getNewPassword();
                $hash = $hasher->hashPassword($user, $newPassword);

                $user->setPassword($hash);
                $manager->persist($user);
                $manager->flush();

                $message = $translator->trans(('Your password has been changed'));

                $this->addFlash(
                    'success',
                    $message
                );

                return $this->redirectToRoute('account_index',['id' => $user->getId(), 'withAlert' => true]);

            }
        }

        return $this->render("account/passwordUpdate.html.twig",[
            'myForm'=>$form->createView()
        ]);


    }

    # Permet de modifier l'avatar de l'utilisateur
    #[Route("/account/img/modify", name : "profile_img_modify")]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    # param Request $request
    # @param EntityManagerInterface $manager
    # @return Response
    public function imgModify(Request $request, EntityManagerInterface $manager, TranslatorInterface $translator)
    {
        $imgModify = new UserImgModify();
        $user = $this->getUser();
        $form = $this->createForm(ImgModifyType::class, $imgModify);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // Permet de supprimer l'image dans le dossier 
            if(!empty($user->getPicture()))
            {
                unlink($this->getParameter('uploads_directory').'/'.$user->getPicture());
            }

            $file = $form['newPicture']->getData();
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
                $user->setPicture($newFilename);
            }

            $manager->persist($user);
            $manager->flush();

            $message = $translator->trans(('Your avatar has been changed'));

            $this->addFlash(
                'success',
                $message
            );

            return $this->redirectToRoute('account_index', ['id' => $user->getId(), 'withAlert' => true]);
        }

        return $this->render("account/imgModify.html.twig",[
            'myForm' => $form->createView()
        ]);

    }

    # Permet de supprimer l'image de l'utilisateur
    #[Route("/account/img/delete", name : "profile_img_delete")]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    # @param EntityManagerInterface $manager
    # @return Response
    public function removeImg(EntityManagerInterface $manager, TranslatorInterface $translator)
    {
        $user = $this->getUser();
        if(!empty($user->getPicture()))
        {
            unlink($this->getParameter('uploads_directory').'/'.$user->getPicture());
            $user->setPicture('');
            $manager->persist($user);
            $manager->flush();

            $message = $translator->trans(('Your avatar has been successfully deleted'));

            $this->addFlash(
                'success',
                $message
            );
        }

        return $this->redirectToRoute('account_index', ['id' => $user->getId(), 'withAlert' => true]);

    }

    
    # Permet à l'utilisateur de se connecter
    #[Route("/login", name : "account_login")]
    public function index(AuthenticationUtils $utils): Response
    {

        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/index.html.twig', [
            'hasError' => $error !== null,
            'username' => $username,
           
        ]);
    }

    # Permet de se déconnecter
    #[Route("/logout", name : "account_logout")]
    public function logout(){
        // ...
    }
}