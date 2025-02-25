<?php

namespace App\Controller;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class FinishedTasksController extends AbstractController
{
    #[Route('/finished/tasks', name: 'app_finished_tasks')]
    public function index(EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $user_id=$session->get('user_id');
        $user = $entityManager->getRepository(User::class)->find($user_id);
        $tasks = $entityManager->getRepository(Task::class)->findBy(['user'=>$user,'isCompleted'=>true]);
        return $this->render('finished_tasks/index.html.twig', [
            'tasks' => $tasks,
        ]);
    
    }
}
