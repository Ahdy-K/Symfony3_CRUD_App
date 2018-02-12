<?php

namespace AK\Todolist\Bundle\Controller;
use AK\Todolist\Bundle\Entity\Todo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;




class TodoController extends Controller
{
    public function todoAction()
    {
        $todos= $this->getDoctrine()->getRepository('AKTodolistBundle:Todo')->findAll();

        return $this->render('AKTodolistBundle:Todo:home.html.twig',array(
            'todos'=> $todos
        ));
    }
    public function createAction(Request $request)
    
    {
        $todo = new Todo;
        $form= $this->createFormBuilder($todo)
        ->add('name', textType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('category', textType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('description', textareaType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('priority', choiceType::class,array('choices'=>array('low'=>'low','medium'=>'medium','high'=>'high'),'attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('dateLimit', dateTimeType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('name', textType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('save it', SubmitType::class,array('attr'=>array('label'=>'Submit','class'=>'btn btn-success','style'=>'margin-bottom:15px')))        
        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $todo->setCreationDate(new\DateTime('now'));
            $em=$this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();
            $request->getSession()->getFlashBag('notice','Done!');
            return $this->redirectToRoute('ak_todolist_homepage');
        }
        return $this->render('AKTodolistBundle:Todo:create.html.twig',array('form'=>$form->createView()));
    }
    public function editAction($id, Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $todo= $em->getRepository('AKTodolistBundle:Todo')->find($id);
        $form= $this->createFormBuilder($todo)
        ->add('name', textType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('category', textType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('description', textareaType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('priority', choiceType::class,array('choices'=>array('low'=>'low','medium'=>'medium','high'=>'high'),'attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('dateLimit', dateTimeType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('name', textType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('save it', SubmitType::class,array('attr'=>array('label'=>'Submit','class'=>'btn btn-success','style'=>'margin-bottom:15px')))        
        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $todo->setCreationDate(new\DateTime('now'));
            $em->flush();
            $request->getSession()->getFlashBag('notice','Updated succesfully!');
            return $this->redirectToRoute('ak_todolist_homepage');
        }
        return $this->render('AKTodolistBundle:Todo:edit.html.twig',array('form'=>$form->createView()));
 
    }
    public function detailsAction($id)
    {
        $todo= $this->getDoctrine()->getRepository('AKTodolistBundle:Todo')->find($id);
        
        return $this->render('AKTodolistBundle:Todo:details.html.twig',array(
            'todo'=>$todo));
    }
    public function deleteAction($id, Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $todo= $em->getRepository('AKTodolistBundle:Todo')->find($id);
        $em->remove($todo);
        $em->flush();
        $request->getSession()->getFlashBag('notice','Todo removed!');
        return $this->redirectToRoute('ak_todolist_homepage');

    }
}
