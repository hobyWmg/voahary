<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Produit;
use AppBundle\Form\ImageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Produit controller.
 *
 * @Route("/voaharyadmin/produit")
 */
class ProduitController extends Controller
{
    /**
     * Lists all produit entities.
     *
     * @Route("/", name="voaharyadmin_produit_index")
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $param = [];
        $produits = $em->getRepository('AppBundle:Produit')->getAllProduct($param);
        // dump($produits);die;
        return $this->render('produit/index.html.twig', array(
            'produits' => $produits,
        ));
    }

    /**
     * Creates a new produit entity.
     *
     * @Route("/new", name="voaharyadmin_produit_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm('AppBundle\Form\ProduitType', $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            $this->addFlash('success', "");
            return $this->redirectToRoute('voaharyadmin_produit_index');
        }

        return $this->render('produit/new.html.twig', array(
            'produit' => $produit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/{id}", name="voaharyadmin_produit_show")
     * @Method("GET")
     */
    public function showAction(Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);

        return $this->render('produit/show.html.twig', array(
            'produit' => $produit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing produit entity.
     *
     * @Route("/{id}/edit", name="voaharyadmin_produit_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);
        $editForm = $this->createForm('AppBundle\Form\ProduitType', $produit);
        $image = new Image();
        $otherImageForm = $this->createForm(ImageType::class,$image);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "");
            return $this->redirectToRoute('voaharyadmin_produit_index');
        }

        return $this->render('produit/edit.html.twig', array(
            'produit' => $produit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'otherImages'=> $produit->getOtherImages(),
            'other_image_form' => $otherImageForm->createView(),
            
        ));
    }

    /**
     * Deletes a produit entity.
     *
     * @Route("/{id}/delete", name="voaharyadmin_produit_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Produit $produit)
    {
        // $form = $this->createDeleteForm($produit);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();
            $this->addFlash('success', "");
        // }

        return $this->redirectToRoute('voaharyadmin_produit_index');
    }

    /**
     * Creates a form to delete a produit entity.
     *
     * @param Produit $produit The produit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Produit $produit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('voaharyadmin_produit_delete', array('id' => $produit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Displays a form to edit an existing produit entity.
     *
     * @Route("/{id}/addImage", name="voaharyadmin_produit_add_other_image")
     * @Method({"POST"})
     */
    public function addOtherImageAction(Request $request, Produit $produit){
        
        $em = $this->getDoctrine()->getManager();
        $image = new Image();
        $form = $this->createForm(ImageType::class,$image);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $produit->addOtherImage($image);
            $em->persist($produit);
            $em->persist($image);
            $em->flush();
            $this->addFlash('success', "");
         return $this->redirectToRoute('voaharyadmin_produit_edit',['id'=> $produit->getId()]);    
        }

    }

    /**
     * Displays a form to edit an existing produit entity.
     *
     * @Route("/{id}/deleteImage/{imgId}", name="voaharyadmin_produit_delete_other_image")
     * @Method({"DELETE"})
     */
    public function deleteOtherImageAction(Request $request, Produit $produit, $imgId){
        $em = $this->getDoctrine()->getManager();
        $otherImage = $em->getRepository('AppBundle:Image')->find($imgId);
        
        $produit->removeOtherImage($otherImage);
        $em->remove($otherImage);   
        $em->flush();
        $this->addFlash('success', "");
         return $this->redirectToRoute('voaharyadmin_produit_edit',['id'=> $produit->getId()]);    
        }
     /**
     * Displays a form to edit an existing produit entity.
     *
     * @Route("/update_order/raw", name="voaharyadmin_update_order")
     * @Method({"GET","POST"})
     */
    public function updateOrderAction(){
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('AppBundle:Produit')->findAll();
        foreach($produits as $k => $p){
                $p->setOrdre($k);
                $em->persist($p);
        }
        $em->flush();
        $this->addFlash('success', "");
        return $this->render('produit/index.html.twig', array(
            'produits' => $produits,
        ));
    }  
     /**
     *
     * @Route("/{id}/updateposition_produit", name="voaharyadmin_update_position_produit")
     * @Method({"POST"})
     */
    public function updatePositionAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $p  = $em->getRepository('AppBundle:Produit')->find($id);
        $number = $request->request->get('ordre');

        try {
            $p->setOrdre($number);
            $em->persist($p);
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
