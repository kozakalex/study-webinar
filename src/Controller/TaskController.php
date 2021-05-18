<?php


namespace App\Controller;


use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/task", name="task")
     */
    public function index(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findAll();

        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $tasks,

        ]);

    }

    /**
     * @Route("/task/edit/{id}", name="task_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Task $task): Response
    {

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task');
        }

        return $this->render('task/edit.html.twig', [
            'post' => $task,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/task/remove/{id}", name="task_remove")
     */
    public function remove(Task $task): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute('task');
    }

    /**
     * @Route("/task/create", name="task_create", methods={"GET"})
     */
    public function create(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task, ['action' => $this->generateUrl('task_save')]);

        return $this->render('task/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/task/save", name="task_save", methods={"POST"})
     */
    public function save(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/task/done/{id}", name="task_done")
     */
//    public function remove(Task $task): Response
//    {
//        $em = $this->getDoctrine()->getManager();
//        $em->remove($task);
//        $em->flush();
//
//        return $this->redirectToRoute('task');
//    }
//


}