<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Entite;
use AppBundle\Form\EntiteType;


/**
 * @Route("/adminarssam")
 */
class EntityController extends Controller
{
   /**
     * @Route("/entity", name="arssam_entity_list")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $search = [];
        // $entity = $em->getRepository('AppBundle:Entite')->getUserByFilters();
        $entity = $em->getRepository('AppBundle:Entite')->findAll();
        // $query = $em->getRepository('AppBundle:User')->getQueryUserByFilters($search);
        // $paginator  = $this->get('knp_paginator');
        // $users = $paginator->paginate(
        //     $query, 
        //     $request->query->getInt('page', 1),2
        // );
        return $this->render('@App/Entite/index.html.twig',['entites'=>$entity]);
    }

    /**
     * @Route("/entity/add", name="arssam_entity_create")
     */
    public function createAction(Request $request)
    {
        $entite = new Entite();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EntiteType::class,$entite);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $em->persist($entite);
           $em->flush();  
           $this->addFlash('success','Opération effectuée avec succès');
           return $this->redirectToRoute('arssam_entity_list');
        }
        return $this->render('AppBundle:Entite:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/entity/edit/{id}", name="arssam_entity_edit")
     */
    public function updateAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entite = $em->getRepository('AppBundle:Entite')->find($id);
        $form = $this->createForm(EntiteType::class,$entite);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $em->flush();    
           $this->addFlash('success','Opération effectuée avec succès');
           return $this->redirectToRoute('arssam_entity_list');
        }
        return $this->render('AppBundle:Entite:update.html.twig', array(
            'form' => $form->createView(),
            'entite' => $entite
        ));
    }

    /**
     * @Route("/entity/delete/{id}", name="arssam_entity_delete")
     */
    public function deleteAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entite = $em->getRepository('AppBundle:Entite')->find($id);
        $em->remove($entite);
        $em->flush();
        $this->addFlash('success','Opération effectuée avec succès');
        return $this->redirectToRoute('arssam_entity_list');
        
    }
}
