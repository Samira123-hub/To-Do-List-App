<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
class HomepageController extends AbstractController
{
    #[Route('/homepage', name: 'app_homepage')]
    public function homepage(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $user_id=$session->get('user_id');
        $user = $entityManager->getRepository(User::class)->find($user_id);
        $quotes = json_decode(file_get_contents($this->getParameter('kernel.project_dir').'/public/quotes.json'), true);
        $index = date('z') % count($quotes);
        $quote = $quotes[$index];
        return $this->render('homepage/index.html.twig', [
            'user' => $user,
            'quote' => $quote,
        ]);
    }

    #[Route('/logout', name: 'app_logout')] 
    public function logout(SessionInterface $session): Response {
     // Détruire la session
        $session->invalidate();
      // Rediriger vers la page d'accueil ou la page de connexion
        return $this->redirectToRoute('app_login');
}
}
