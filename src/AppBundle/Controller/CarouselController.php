<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Carousel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\CarouselType;
use AppBundle\Form\Handler\CarouselEntityHandler;
use AppBundle\Form\Handler\BaseHandler;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Carousel controller.
 *
 * @Route("voaharyadmin/carousel")
 */
class CarouselController extends Controller
{
    /**
     * Lists all carousel entities.
     *
     * @Route("/", name="voaharyadmin_carousel_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $carousels = $em->getRepository('AppBundle:Carousel')->findAll();

        return $this->render('carousel/index.html.twig', array(
            'carousels' => $carousels,
        ));
    }

    /**
     * Creates a new carousel entity.
     *
     * @Route("/new", name="voaharyadmin_carousel_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(CarouselType::class, new Carousel());
        $formHandler = new CarouselEntityHandler($form, $request, $em);

        if ($formHandler->process()) {
            $this->addFlash('success', "");
            $url = $this->generateUrl('voaharyadmin_carousel_index');
            return $this->redirect($url);
        }

        return $this->render('carousel/new.html.twig',[
            'form'   => $formHandler->getForm()->createView(),
        ]);
    }

    /**
     * Finds and displays a carousel entity.
     *
     * @Route("/{id}", name="voaharyadmin_carousel_show")
     * @Method("GET")
     */
    public function showAction(Carousel $carousel)
    {
        $deleteForm = $this->createDeleteForm($carousel);

        return $this->render('carousel/show.html.twig', array(
            'carousel' => $carousel,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing carousel entity.
     *
     * @Route("/{id}/edit", name="voaharyadmin_carousel_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Carousel $carousel)
    {
        $deleteForm = $this->createDeleteForm($carousel);
        $editForm = $this->createForm('AppBundle\Form\CarouselType', $carousel);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "");
            return $this->redirectToRoute('voaharyadmin_carousel_index');
        }

        return $this->render('carousel/edit.html.twig', array(
            'carousel' => $carousel,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a carousel entity.
     *
     * @Route("/{id}/delete", name="voaharyadmin_carousel_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Carousel $carousel)
    {
        // $form = $this->createDeleteForm($carousel);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($carousel);
            $em->flush();
            $this->addFlash('success', "");
        // }

        return $this->redirectToRoute('voaharyadmin_carousel_index');
    }

    /**
     * Creates a form to delete a carousel entity.
     *
     * @param Carousel $carousel The carousel entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Carousel $carousel)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('voaharyadmin_carousel_delete', array('id' => $carousel->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     *
     * @Route("/{id}/updateposition", name="voaharyadmin_update_position")
     * @Method({"POST"})
     */
    public function updatePositionAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $photo  = $em->getRepository('AppBundle:Carousel')->find($id);
        $number = $request->request->get('ordre');

        try {
            $photo->setOrdre($number);
            $em->persist($photo);
            $em->flush();

            $message = 'L\'ordre a été mis à jour';
        } catch (\Exception $exc) {
            $error = 'Il y a une erreur lors du mis à jour';
        }
        $response = new JsonResponse();
        $response->setData(array(
            'message' => isset($message) ? $message : '',
            'error' => isset($error) ? $error : ''
        ));
        return $response;
    }
}
