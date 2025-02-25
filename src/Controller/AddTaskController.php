<?php

namespace App\Controller;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;

class AddTaskController extends AbstractController
{

    #[Route('/add/task', name: 'app_add_task')]
    public function index(Request $request,
    EntityManagerInterface $entityManager,SessionInterface $session): Response
    {

        if($request->isMethod('POST')){

            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $priority = $request->request->get('priority');
            $duration = $request->request->get('duration');

            $task = new Task();
            $task->setTitle($title);
            $task->setDescription($description);
            $task->setPriority($priority);
            $task->setDuration($duration);
            $task->setTitle($title);
            $task->setCompleted(false);
            $task->setCreatedAt(new \DateTimeImmutable());

            //
            $user_id=$session->get('user_id');
            $user = $entityManager->getRepository(User::class)->find($user_id);
            $task->setUser($user);
            
            $entityManager ->persist($task);
            $entityManager->flush();
            $this->addFlash('success','Task added successefuly');
                return $this -> redirectToRoute('app_my_tasks');

        }
        return $this->render('add_task/index.html.twig');
    }
}
