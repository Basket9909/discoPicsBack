<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ImgModifyType;
use App\Service\JWTService;
use App\Entity\UserImgModify;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use App\Service\SendMailService;
use App\Repository\UsersRepository;
use App\Form\ModificationAccountType;
use App\Repository\ImagesRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PublicationRepository;
use Faker\Guesser\Name;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    # Permet d'enregistrer un nouveaux user
    #[Route("/registration", name : "account_register")]
    # param Request $request
    # param EntityManagerInterface $manager
    # param UserPasswordHasherInterface $hasher
    # return Response
    public function register(Request $request,EntityManagerInterface $manager,UserPasswordHasherInterface $hasher, TranslatorInterface $translator, SendMailService $mail, JWTService $jwt)
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

            // On génère le JWT de l'utilisateur
            // On crée le Header
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            // On crée le Payload
            $payload = [
                'user_id' => $user->getId()
            ];

            // On génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // On envoie un mail
            $mail->send(
                'no-reply@discopics.net',
                $user->getMail(),
                $translator->trans(('Activate your account')),
                'register',
                compact('user', 'token')
            );

            $message = $translator->trans(("You have been successfully registered, An email has been sent to you to activate your account"));

            $this->addFlash(
                'success',
                $message
            );

            return $this->redirectToRoute('account_index',['id' => $user->getId(), 'withAlert' => true]);
        }


        return $this->render("account/new.html.twig",[
            'myForm' => $form->createView()
        ]);
    }


    
    # Permet d'envoyer un mail de confirmation de compte
    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UsersRepository $usersRepository, EntityManagerInterface $manager,TranslatorInterface $translator): Response
    {
        //On vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))){
            // On récupère le payload
            $payload = $jwt->getPayload($token);

            // On récupère le user du token
            $user = $usersRepository->find($payload['user_id']);

            //On vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if($user && !$user->getIsVerified()){
                $user->setIsVerified(true);
                $manager->flush($user);
                $this->addFlash('success', $translator->trans(("Activated account")));
                return $this->redirectToRoute('account_index',['id' => $user->getId(), 'withAlert' => true]);
            }
        }
        // Ici un problème se pose dans le token
        $this->addFlash('danger', $translator->trans(("The token is invalid or has expired")));
        return $this->redirectToRoute('homepage');
    }

    # Permet de renvoyer un mail de confirmation de compte
    #[Route("/resendverif", name:'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UsersRepository $usersRepository, TranslatorInterface $translator)
    {
        $user = $this->getUser();

        if(!$user){

            $this->addFlash('danger', $translator->trans(("You must be logged in to access this page")));
            return $this->redirectToRoute('account_login');
        }

        if($user->getIsVerified()){

            $this->addFlash('warning',  $translator->trans(("This account is already activated")));
            return $this->redirectToRoute('account_index',['id' => $user->getId(), 'withAlert' => true]);
        }

         // On génère le JWT de l'utilisateur
            // On crée le Header
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            // On crée le Payload
            $payload = [
                'user_id' => $user->getId()
            ];

            // On génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // On envoie un mail
            $mail->send(
                'no-reply@discopics.net',
                $user->getMail(),
                $translator->trans(('Activate your account')),
                'register',
                compact('user', 'token')
            );

            $this->addFlash('warning', $translator->trans(('The validation email has been resent')));
            return $this->redirectToRoute('homepage');
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

    #Permet d'afficher tout les posts d'un utilisateur
    #[Route("/account/{id}/posts/all", name : "show_all_posts_user")]
    #[Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")]
    public function allPostsforuser(PublicationRepository $publicationRepo,$id, ImagesRepository $imagesRepo ){

        return $this->render('account/fullPosts.html.twig',[
            'publications'=> $publicationRepo->getPublicationForUserWithAllResult($id),
            'images'=> $imagesRepo->getImagesForUserWithAllResult($id),
            'user' => $this->getUser()
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