<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="category_list")
     */
    public function listAction()
    {
        $categories = $this->getDoctrine()
                ->getRepository('AppBundle:Category')
                ->findAll();
        return $this->render('category/index.html.twig', array(
            'categories' => $categories
        ));
    }
    
    /**
     * @Route("/category/create", name="category_create")
     */
    public function createAction(Request $request)
    {
        $category = new Category;
        
        $form = $this->createFormBuilder($category)
                -> add(
                        'name', 
                        TextType::class, 
                        array(
                            'attr' => array(
                                'class'=> 'form-control', 
                                'style' => 'margin-bottom:15px; width: 300px'
                            )
                        ))
                -> add('save', SubmitType::class, array('label' => 'Create Category', 'attr' => array('class'=> 'btn btn-primary', 'style' => 'margin-bottom:15px')))
                -> getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Get Data
            $name = $form['name']->getData();
            $category->setName($name);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            
            $this->addFlash(
                    'notice',
                    'Category Added'
            );
            
            return $this->redirectToRoute('category_list');
        }
        return $this->render('category/create.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/category/edit/{id}", name="category_edit")
     */
    public function editAction($id, Request $request)
    {
        $category = $this->getDoctrine()
                ->getRepository('AppBundle:Category')
                ->find($id);
        
        $category->setName($category->getName());
            
        $form = $this->createFormBuilder($category)
                -> add('name', TextType::class, array('attr' => array('class'=> 'form-control', 'style' => 'margin-bottom:15px')))
                -> add('save', SubmitType::class, array('label' => 'Update Category', 'attr' => array('class'=> 'btn btn-primary', 'style' => 'margin-bottom:15px')))
                -> getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Get Data
            $name = $form['name']->getData();
            
            $em = $this->getDoctrine()->getManager();
            $category = $em->getRepository('AppBundle:Category')->find($id);
            
            $category->setName($name);
            
            $em->persist($category);
            $em->flush();
            
            $this->addFlash(
                    'notice',
                    'Category Edit'
            );
            
            return $this->redirectToRoute('category_list');
        }
        
        return $this->render('category/edit.html.twig', array(
            'form' => $form->createView(),
            'category' => $category
        ));
    }
    
    /**
     * @Route("/category/details/{id}", name="category_details")
     */
    public function detailsAction($id)
    {
        $category = $this->getDoctrine()
                ->getRepository('AppBundle:Category')
                ->find($id);
        
        return $this->render('category/details.html.twig', array(
            'category' => $category
        ));
    }
    
    
    /**
     * @Route("/category/delete/{id}", name="category_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);
        $em->remove($category);
        $em->flush();
        
        $this->addFlash(
                    'notice',
                    'Category Deleted'
            );
        
        return $this->redirectToRoute('category_list');
    }
}
