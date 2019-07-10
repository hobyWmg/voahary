<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dgd;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\DgdType;

/**
 * @Route("/adminarssam")
 */
class DgdController extends Controller
{
    /**
     * Lists all dgd entities.
     *
     * @Route("/douane", name="arssam_dgd_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $dgds = $em->getRepository('AppBundle:Dgd')->findAll();
        return $this->render('@App/Dgd/index.html.twig',['dgds'=>$dgds]);
    }

    /**
     * @Route("/douane/add", name="arssam_dgd_create")
     */
    public function createAction(Request $request)
    {
        $userConected = $this->getUser();
        $dgd = new Dgd();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(DgdType::class,$dgd);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** end */
            $em->persist($dgd);
            $em->flush();

            $this->addFlash('success','Opération effectuée avec succès');
            return $this->redirectToRoute('arssam_dgd_list');
        }
        return $this->render('AppBundle:Dgd:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
