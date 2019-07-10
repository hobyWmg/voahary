<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Typologie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Typologie controller.
 *
 * @Route("adminarssam/typologie")
 */
class TypologieController extends Controller
{
    /**
     * Lists all typologie entities.
     *
     * @Route("/", name="adminarssam_typologie_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typologies = $em->getRepository('AppBundle:Typologie')->findAll();

        return $this->render('typologie/index.html.twig', array(
            'typologies' => $typologies,
        ));
    }

    /**
     * Creates a new typologie entity.
     *
     * @Route("/new", name="adminarssam_typologie_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typologie = new Typologie();
        $form = $this->createForm('AppBundle\Form\TypologieType', $typologie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typologie);
            $em->flush();

            return $this->redirectToRoute('adminarssam_typologie_show', array('id' => $typologie->getId()));
        }

        return $this->render('typologie/new.html.twig', array(
            'typologie' => $typologie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a typologie entity.
     *
     * @Route("/{id}", name="adminarssam_typologie_show")
     * @Method("GET")
     */
    public function showAction(Typologie $typologie)
    {
        $deleteForm = $this->createDeleteForm($typologie);

        return $this->render('typologie/show.html.twig', array(
            'typologie' => $typologie,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing typologie entity.
     *
     * @Route("/{id}/edit", name="adminarssam_typologie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Typologie $typologie)
    {
        $deleteForm = $this->createDeleteForm($typologie);
        $editForm = $this->createForm('AppBundle\Form\TypologieType', $typologie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('adminarssam_typologie_edit', array('id' => $typologie->getId()));
        }

        return $this->render('typologie/edit.html.twig', array(
            'typologie' => $typologie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a typologie entity.
     *
     * @Route("/{id}", name="adminarssam_typologie_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Typologie $typologie)
    {
        $form = $this->createDeleteForm($typologie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typologie);
            $em->flush();
        }

        return $this->redirectToRoute('adminarssam_typologie_index');
    }

    /**
     * Creates a form to delete a typologie entity.
     *
     * @param Typologie $typologie The typologie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Typologie $typologie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('adminarssam_typologie_delete', array('id' => $typologie->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
