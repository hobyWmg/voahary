<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Form\VeliranoType;
use AppBundle\Entity\Velirano;

class VeliranoController extends Controller
{
    /**
     * @Route("/admin/velirano", name="velirano_list")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $allVelirano = $em->getRepository('AppBundle:Velirano')->findAll(); 
        return $this->render('admin/velirano/list.html.twig',[
            'veliranoList' => $allVelirano
        ]);
    }

    /**
     * @Route("/admin/velirano-edit/{id}", name="velirano_edit")
     */
    public function editAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $velirano = $em->getRepository('AppBundle:Velirano')->find($id);
        $form = $this->createForm(VeliranoType::class,$velirano);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success','Velirano enregistré');
            return $this->redirectToRoute('velirano_list');
        }

        return $this->render('admin/velirano/edit.html.twig',[
            'velirano' => $velirano,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/velirano-add", name="velirano_add")
     */
     public function addAction(Request $request)
     {
         $em = $this->getDoctrine()->getManager();
         $velirano = new Velirano();
         $form = $this->createForm(VeliranoType::class,$velirano);
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
            $velirano->setPercent(0);
            $em->persist($velirano);
             $em->flush();
             $this->addFlash('success','Velirano enregistré');
             return $this->redirectToRoute('velirano_list');
         }
 
         return $this->render('admin/velirano/add.html.twig',[
             'form' => $form->createView()
         ]);
     }

     /**
     * @Route("/admin/velirano-delete/{id}", name="velirano_delete")
     */
    public function deleteAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $velirano = $em->getRepository('AppBundle:Velirano')->find($id);
        $em->remove($velirano);
        $em->flush();
        $this->addFlash('success','Velirano supprimé');
        return $this->redirectToRoute('velirano_list');

    }


}     
