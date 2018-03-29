<?php

namespace TaskBundle\Controller;


use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use TaskBundle\Entity\Task;
use TaskBundle\Form\TaskType;

class TaskController extends FOSRestController {

    /**
    * Get all the tasks
    * @return array
    *
    * @View()
    * @Get("/tasks")
    */
    public function getTasksAction(){

        $tasks = $this->getDoctrine()->getRepository("TaskBundle:Task")->findAll();
        return array('tasks' => $tasks);
    }

    /**
     * Get a task by ID
     * @param Task $task
     * @return array
     *
     * @View()
     * @ParamConverter("task", class="TaskBundle:Task")
     * @Get("/task/{id}",)
     */
    public function getTaskAction(Task $task){

        return array('task' => $task);

    }

    /**
     * Create a new Task
     * @var Request $request
     * @return View|array
     *
     * @View()
     * @Post("/task")
     */
    public function postTaskAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(new TaskType(), $task);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return array("task" => $task);

        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Edit a Task
     * Put action
     * @var Request $request
     * @var Task $task
     * @return array
     *
     * @View()
     * @ParamConverter("task", class="TaskBundle:Task")
     * @Put("/task/{id}")
     */
    public function putTaskAction(Request $request, Task $task)
    {
        $form = $this->createForm(new TaskType(), $task);
        $form->submit($request);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($task);
            $em->flush();

            return array("task" => $task);
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Delete a Task
     * Delete action
     * @var Task $task
     * @return array
     *
     * @View()
     * @ParamConverter("task", class="TaskBundle:Task")
     * @Delete("/task/{id}")
     */
    public function deleteTaskAction(Task $task)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        return array("status" => "Deleted");
    }
} 