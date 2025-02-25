<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginController extends AbstractController
{
    #[Route("/", name: "app_login")]
    public function login(Request $request, EntityManagerInterface $entityManager,  UserPasswordHasherInterface $passwordHasher,SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {                // Ajouter un message d'erreur si l'utilisateur n'existe pas ou si le mot de passe est incorrect
                $this->addFlash('error', 'Incorrect username or password.');
                return $this->redirectToRoute('app_login');
            }
            
            $session->set('user_id',$user->getId());
            

            $this->addFlash('success', 'Connection successful!');
            return $this->redirectToRoute('app_homepage'); // Rediriger vers une page après connexion
        }

        return $this->render('login/index.html.twig');
    }
    

}
