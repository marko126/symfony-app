<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\TodoType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TodoController extends Controller
{
    /**
     * @Route("/", name="todo_list")
     */
    public function listAction()
    {
        $todos = $this->getDoctrine()
                ->getRepository('AppBundle:Todo')
                ->findAll();
        // Test
        $a = 1;
		$c = 3;
        return $this->render('todo/index.html.twig', array(
            'todos' => $todos
        ));
    }
    
    /**
     * @Route("/todo/create", name="todo_create")
     */
    public function createAction(Request $request)
    {
        $todo = new Todo;
        
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Get Data
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $assignee = $form['assignee']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $due_date = $form['due_date']->getData();
            
            $now = new \DateTime('now');
            
            $todo->setName($name);
            
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);
            $todo->setCreateDate($now);
            $todo->setCategory($category);
            $todo->setAssignee($assignee);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();
            
            $this->addFlash(
                    'notice',
                    'Todo Added'
            );
            
            return $this->redirectToRoute('todo_list');
        }
        
        return $this->render('todo/create.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/todo/edit/{id}", name="todo_edit")
     */
    public function editAction($id, Request $request)
    {
        $todo = $this->getDoctrine()
                ->getRepository('AppBundle:Todo')
                ->find($id);
        $now = new \DateTime('now');
        
        $todo->setName($todo->getName());
        $todo->setCategory($todo->getCategory());
        $todo->setAssignee($todo->getAssignee());
        $todo->setDescription($todo->getDescription());
        $todo->setPriority($todo->getPriority());
        $todo->setDueDate($todo->getDueDate());
        $todo->setCreateDate($now);
            
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Get Data
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $due_date = $form['due_date']->getData();
            $assignee = $form['assignee']->getData();
            
            $now = new \DateTime('now');
            
            $em = $this->getDoctrine()->getManager();
            $todo = $em->getRepository('AppBundle:Todo')->find($id);
            
            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);
            $todo->setCreateDate($now);
            $todo->setAssignee($assignee);
            
            
            $em->persist($todo);
            $em->flush();
            
            $this->addFlash(
                    'notice',
                    'Todo Edit'
            );
            
            return $this->redirectToRoute('todo_list');
        }
        
        return $this->render('todo/edit.html.twig', array(
            'form' => $form->createView(),
            'todo' => $todo
        ));
    }
    
    /**
     * @Route("/todo/details/{id}", name="todo_details")
     */
    public function detailsAction($id)
    {
        $todo = $this->getDoctrine()
                ->getRepository('AppBundle:Todo')
                ->find($id);
        
        return $this->render('todo/details.html.twig', array(
            'todo' => $todo
        ));
    }
    
    /**
     * @Route("/todo/details/{id}/notes", name="todo_apilist")
     * @Method("GET")
     */
    public function getApilistAction($id)
    {
        /*$todos = $this->getDoctrine()
                ->getRepository('AppBundle:Todo')
                ->findAll();
        */
        $json_todos = [
            ["id" => "1", "username" => $id]
        ];
        /*
        foreach ($todos as $key => $todo) {
            $json_todos[$key] = $todo;
        }
        */
        $data = [
            'todos' => $json_todos
        ];
        
        return new JsonResponse($data);
    }
    
    /**
     * @Route("/todo/delete/{id}", name="todo_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $todo = $em->getRepository('AppBundle:Todo')->find($id);
        $em->remove($todo);
        $em->flush();
        
        $this->addFlash(
                    'notice',
                    'Todo Deleted'
            );
        
        return $this->redirectToRoute('todo_list');
    }
    
    /**
     * @Route("/todo/addtask/{id}", name="todo_addtask")
     */
    public function addtaskAction($id, Request $request)
    {
        $todo = new Todo;
        
        $assignee = $this->getDoctrine()
                ->getRepository('AppBundle:Assignee')
                ->find($id);
        
        $form = $this->createForm(TodoType::class, $todo);
        $form->remove('assignee');
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Get Data
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $due_date = $form['due_date']->getData();
            
            $now = new \DateTime('now');
            
            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);
            $todo->setCreateDate($now);
            $todo->setAssignee($assignee);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();
            
            $this->addFlash(
                    'notice',
                    'Todo Added'
            );
            
            return $this->redirectToRoute('assignee_details', array('id'=>$id));
        }
        
        return $this->render('todo/addtask.html.twig', array(
            'form' => $form->createView(),
            'assignee' => $assignee
        ));
    }
}
