<?php

namespace App\Controller;
use App\Entity\User;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegisterType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
        ):Response{

            if($request->isMethod('POST')){
                $firstName = $request->request->get('firstName');
                $familyName = $request->request->get('familyName');
                $email = $request->request->get('email');
                $password = $request->request->get('password');
                $confirmPassword = $request->request->get('confirm_password');


                if($password !== $confirmPassword){
                    $this->addFlash('error', 'error password❌');
                    return $this->redirectToRoute('app_register');
                }


                $user = new User();
                $username = $firstName." ".$familyName;
                $user->setUsername($username);
                $user->setEmail($email);
                $user->setPassword($passwordHasher->hashPassword($user,$password));

                $user->setCreatedAt(new \DateTimeImmutable());

                $imageFile = $request->files->get('image');
                if($imageFile){
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFileName  = $slugger->slug($originalFilename);
                    $newFileName = $safeFileName.'-'.uniqid().'.'.$imageFile->guessExtension();
                    try{
                        $imageFile->move(
                            $this->getParameter('profile_directory'),
                            $newFileName
                        );
                    }catch(FileException $e){
                        $this->addFlash('error', 'error image❌');
                        return $this->redirectToRoute('app_register');
                    }
                    $user->setImage($newFileName);
                }



                $entityManager ->persist($user);
                $entityManager->flush();


                $this->addFlash('success','Successful registration. You can now log in.');
                return $this -> redirectToRoute('app_login');
            }
    
        return $this->render('register/index.html.twig');
    }
}
