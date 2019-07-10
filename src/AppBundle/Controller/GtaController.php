<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Gta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\GtaType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/adminarssam")
 */
class GtaController extends Controller
{
    /**
     * Lists all gtum entities.
     *
     * @Route("/gendarmerie", name="arssam_gta_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $gtas = $em->getRepository('AppBundle:Gta')->findAll();
        return $this->render('@App/Gta/index.html.twig',['gtas'=>$gtas]);
    }
    /**
     * @Route("/gendarmerie/add", name="arssam_gta_create")
     */
    public function createAction(Request $request)
    {
        $userConected = $this->getUser();
        $gta = new Gta();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(GtaType::class,$gta);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** end */
            $em->persist($gta);
            $em->flush();

            $this->addFlash('success','Opération effectuée avec succès');
            return $this->redirectToRoute('arssam_gta_list');
        }
        return $this->render('AppBundle:Gta:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
