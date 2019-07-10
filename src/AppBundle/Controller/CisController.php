<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cis;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\CisType;

/**
 * Ci controller.
 *
 * @Route("/adminarssam")
 */
class CisController extends Controller
{
    /**
     * Lists all ci entities.
     *
     * @Route("/cis", name="arssam_cis_list")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // $cis = $em->getRepository('AppBundle:Cis')->findAll();
        $query = $em->getRepository('AppBundle:Cis')->findByFilter();
        $paginator  = $this->get('knp_paginator');
        $cis = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),15
        );

        return $this->render('@App/Cis/index.html.twig',['cis'=>$cis]);
    }

    /**
     * Creates a new ci entity.
     *
     * @Route("/cis/new", name="arssam_cis_create")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $cis = new Cis();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CisType::class,$cis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** end */
            $em->persist($cis);
            $em->flush();

            $this->addFlash('success','Opération effectuée avec succès');
            return $this->redirectToRoute('arssam_cis_list');
        }
        return $this->render('AppBundle:Cis:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
