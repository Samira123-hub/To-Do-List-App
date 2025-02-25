<?php
namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class MyTasksController extends AbstractController
{
    #[Route('/my/tasks', name: 'app_my_tasks')]
    public function index(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {   
        $user_id = $session->get('user_id');
        $user = $entityManager->getRepository(User::class)->find($user_id);
        $tasks = $entityManager->getRepository(Task::class)->findBy(['user' => $user]);
        return $this->render('my_tasks/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/tasks/done/{id<\d+>}', name: 'task_done', methods: ["POST"])]
    public function markAsDone(int $id, EntityManagerInterface $entityManager)
    {
        error_log("markAsDone called with id: $id");
        $task = $entityManager->getRepository(Task::class)->find($id);
        if (!$task) {
            return new JsonResponse(['status' => 'error', 'message' => 'Task not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        $task->setCompleted(true);
        $entityManager->flush();
        error_log("Task with id: $id marked as done");
        return new JsonResponse(['status' => 'done']);
    }

    #[Route('/tasks/remove/{id<\d+>}', name: 'task_remove', methods: ["DELETE"])]
    public function removeTask(int $id, EntityManagerInterface $entityManager)
    {
        $task = $entityManager->getRepository(Task::class)->find($id);
        if (!$task) {
            return new JsonResponse(['status' => 'error', 'message' => 'Task not found'], JsonResponse::HTTP_NOT_FOUND);
        }
        $entityManager->remove($task);
        $entityManager->flush();
        return new JsonResponse(['status' => 'removed']);
    }

    #[Route('/tasks/update/{id<\d+>}', name: 'task_update', methods: ["GET", "POST"])]
    public function updateTask(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = $entityManager->getRepository(Task::class)->find($id);
        if (!$task) {
            throw $this->createNotFoundException('The task does not exist');
        }

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $priority = $request->request->get('priority');
            $duration = $request->request->get('duration');

            $task->setTitle($title);
            $task->setDescription($description);
            $task->setPriority($priority);
            $task->setDuration($duration);

            $entityManager->flush();

            return $this->redirectToRoute('app_my_tasks');
        }

        return $this->render('update_task/index.html.twig', [
            'task' => $task,
        ]);
    }
}
