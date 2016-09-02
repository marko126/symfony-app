<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Assignee;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AssigneeController extends Controller
{
    /**
     * @Route("/assignees", name="assignee_list")
     */
    public function listAction()
    {
        $assignees = $this->getDoctrine()
                ->getRepository('AppBundle:Assignee')
                ->findAll();
        return $this->render('assignee/index.html.twig', array(
            'assignees' => $assignees
        ));
    }
    
    /**
     * @Route("/assignee/create", name="assignee_create")
     */
    public function createAction(Request $request)
    {
        $assignee = new Assignee;
        
        $form = $this->createFormBuilder($assignee)
                -> add(
                        'first_name', 
                        TextType::class, 
                        array(
                            'attr' => array(
                                'class'=> 'form-control', 
                                'style' => 'margin-bottom:15px'
                            ), 
                        ))
                -> add(
                        'last_name', 
                        TextType::class, 
                        array(
                            'attr' => array(
                                'class'=> 'form-control', 
                                'style' => 'margin-bottom:15px'
                            ), 
                        ))
                -> add(
                        'email', 
                        EmailType::class, 
                        array(
                            'attr' => array(
                                'class'=> 'form-control', 
                                'style' => 'margin-bottom:15px'
                            ), 
                        ))
                -> add(
                        'password', 
                        PasswordType::class, 
                        array(
                            'attr' => array(
                                'class'=> 'form-control', 
                                'style' => 'margin-bottom:15px'
                            )
                        ))
                -> add('save', SubmitType::class, array('label' => 'Create Assignee', 'attr' => array('class'=> 'btn btn-primary', 'style' => 'margin-bottom:15px')))
                -> getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Get Data
            $firstName = $form['first_name']->getData();
            $lastName = $form['last_name']->getData();
            $email = $form['email']->getData();
            $password = $form['password']->getData();
            
            $assignee->setFirstName($firstName);
            $assignee->setLastName($lastName);
            $assignee->setEmail($email);
            $assignee->setPassword(md5($password));
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($assignee);
            $em->flush();
            
            $this->addFlash(
                    'notice',
                    'Assignee Added'
            );
            
            return $this->redirectToRoute('assignee_list');
        }
        
        return $this->render('assignee/create.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/assignee/edit/{id}", name="assignee_edit")
     */
    public function editAction($id, Request $request)
    {
        $assignee = $this->getDoctrine()
                ->getRepository('AppBundle:Assignee')
                ->find($id);
        
        $assignee->setFirstName($assignee->getFirstName());
        $assignee->setLastName($assignee->getLastName());
        $assignee->setEmail($assignee->getEmail());
            
        $form = $this->createFormBuilder($assignee)
                -> add(
                        'first_name', 
                        TextType::class, 
                        array(
                            'attr' => array(
                                'class'=> 'form-control', 
                                'style' => 'margin-bottom:15px'
                            ), 
                        ))
                -> add(
                        'last_name', 
                        TextType::class, 
                        array(
                            'attr' => array(
                                'class'=> 'form-control', 
                                'style' => 'margin-bottom:15px'
                            ), 
                        ))
                -> add(
                        'email', 
                        EmailType::class, 
                        array(
                            'attr' => array(
                                'class'=> 'form-control', 
                                'style' => 'margin-bottom:15px'
                            ), 
                        ))
                -> add('save', SubmitType::class, array('label' => 'Edit Assignee', 'attr' => array('class'=> 'btn btn-primary', 'style' => 'margin-bottom:15px')))
                -> getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Get Data
            $firstName = $form['first_name']->getData();
            $lastName = $form['last_name']->getData();
            $email = $form['email']->getData();
            
            $em = $this->getDoctrine()->getManager();
            $assignee = $em->getRepository('AppBundle:Assignee')->find($id);
            
            $assignee->setFirstName($firstName);
            $assignee->setLastName($lastName);
            $assignee->setEmail($email);
            
            $em->persist($assignee);
            $em->flush();
            
            $this->addFlash(
                    'notice',
                    'Assignee Edit'
            );
            
            return $this->redirectToRoute('assignee_list');
        }
        
        return $this->render('assignee/edit.html.twig', array(
            'form' => $form->createView(),
            'assignee' => $assignee
        ));
    }
    
    /**
     * @Route("/assignee/details/{id}", name="assignee_details")
     */
    public function detailsAction($id)
    {
        $assignee = $this->getDoctrine()
                ->getRepository('AppBundle:Assignee')
                ->find($id);
        
        if (!$assignee) {
                throw $this->createNotFoundException(
                        'No assignee found for id '.$id
                );
        }
        
        return $this->render('assignee/details.html.twig', array(
            'assignee' => $assignee
        ));
    }
    
    /**
     * @Route("/assignee/delete/{id}", name="assignee_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $assignee = $em->getRepository('AppBundle:Assignee')->find($id);
        $em->remove($assignee);
        $em->flush();
        
        $this->addFlash(
                    'notice',
                    'Assignee Deleted'
            );
        
        return $this->redirectToRoute('assignee_list');
    }
    
}
