<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use AppBundle\Form\Type\PriorityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                -> add(
                        'name', 
                        TextType::class, 
                        array(
                            'attr' => array(
                                'class'=> 'form-control', 
                                'style' => 'margin-bottom:15px'
                            ), 
                            'label' => 'Naziv'
                        ))
                -> add(
                        'category', 
                        EntityType::class, 
                        array(
                            'class' => 'AppBundle:Category',
                            /*'query_builder' => function (EntityRepository $er) {
                                return $er->createQueryBuilder('n')
                                    ->orderBy('n.name', 'ASC');
                            },*/
                            'choice_label' => 'name',
                            'attr' => array(
                                'class'=> 'form-control', 
                                'style' => 'margin-bottom:15px'
                            )
                        ))
                -> add(
                        'assignee', 
                        EntityType::class, 
                        array(
                            'class' => 'AppBundle:Assignee',
                            'choice_label' => 'firstName',
                            'attr' => array(
                                'class'=> 'form-control', 
                                'style' => 'margin-bottom:15px'
                            )
                        ))
                -> add(
                        'description', 
                        TextareaType::class, 
                        array(
                            'attr' => array(
                                'class'=> 'form-control', 
                                'style' => 'margin-bottom:15px'
                            )
                        ))
                -> add(
                        'priority', 
                        PriorityType::class, 
                        array(
                            'placeholder' => 'Choose a priority',
                            'attr' => array(
                                'class'=> 'form-control', 
                                'style' => 'margin-bottom:15px'
                            )
                        ))
                -> add(
                        'due_date', 
                        DateTimeType::class, 
                        array(
                            'attr' => array(
                                'class'=> 'formcontrol', 
                                'style' => 'margin-bottom:15px'
                            )
                        ))
                -> add(
                        'save', 
                        SubmitType::class, 
                        array(
                            'label' => 'Create Todo', 
                            'attr' => array(
                                'class'=> 'btn btn-primary', 
                                'style' => 'margin-bottom:15px'
                            )
                        ))
                ;
    }

}

