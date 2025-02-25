<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(Request $request, SessionInterface $session, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user_id = $session->get('user_id');
            $updated_user = $entityManager->getRepository(User::class)->find($user_id);

            $username = $form->get('username')->getData();
            if ($username) {
                $updated_user->setUsername($username);
            }

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('profile_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error uploading image');
                    return $this->redirectToRoute('app_profil');
                }

                $updated_user->setImage($newFilename);
            }

            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();
            if ($email || $password) {
                if($email){
                $updated_user->setEmail($email);
                }
                if($password){
                $updated_user->setPassword($passwordHasher->hashPassword($updated_user, $password));
                }
                

                $entityManager->persist($updated_user);
                $entityManager->flush();

                return $this->redirectToRoute('app_login');
            }

            $entityManager->persist($updated_user);
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('Profil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
