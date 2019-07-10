<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dvsse;
use AppBundle\Entity\Cas;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\DvsseType;
use AppBundle\Form\CasType;
use Symfony\Component\Intl\Intl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Dvsse controller.
 *
 * @Route("/adminarssam")
 */
class DvsseController extends Controller
{
    /**
     * Lists all dvsse entities.
     *
     * @Route("/dvsse", name="arssam_dvsse_list")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dvsses = $em->getRepository('AppBundle:Dvsse')->findAll();
        $listePays = Intl::getRegionBundle()->getCountryNames();
        return $this->render('@App/Dvsse/index.html.twig',
            [
                'dvsses'=>$dvsses,
                'listePays'  =>$listePays
            ]);

    }

    /**
     * Creates a new dvsse entity.
     *
     * @Route("dvsse/new", name="arssam_dvsse_create")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dvsse = new Dvsse();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(DvsseType::class,$dvsse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** end */
            $em->persist($dvsse);
            $em->flush();

            $this->addFlash('success','Opération effectuée avec succès');
            return $this->redirectToRoute('arssam_dvsse_list');
        }
        return $this->render('AppBundle:Dvsse:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays an BonPlan entity.
     *
     * @Route("dvsse/cas/{id}", name="arssam_dvsse_show", requirements={"id": "\d+"})
     * @Method("GET")
     *
     */
    public function showAction(Dvsse $dvsse)
    {
        $cas = $dvsse->getCas();
        $listePays = Intl::getRegionBundle()->getCountryNames();
        return $this->render('AppBundle:Dvsse:show.html.twig', array(
            'dvsse' => $dvsse,
            'cas' => $cas,
            'listePays' => $listePays
        ));
    }

    /**
     * Creates a new ContenuBonPlan entity.
     *
     * @Route("dvsse/cas/{dvsse_id}/new", name="arssam_dvsse_casnew")
     * @ParamConverter("dvsse", class="AppBundle:Dvsse", options={"id" = "dvsse_id"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Dvsse    $dvsse
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newCasAction(Request $request, $dvsse)
    {

        $em = $this->getDoctrine()->getManager();
        $cas = new Cas();
        $cas->setDvsse($dvsse);
        $form = $this->createForm(CasType::class, $cas);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($cas);
            $em->flush();

            $this->addFlash('success','Opération effectuée avec succès');
            return $this->redirectToRoute('arssam_dvsse_show', array('id' => $dvsse->getId()));
        }
        return $this->render('AppBundle:Dvsse:newCas.html.twig', array(
            'action' => $this->generateUrl('arssam_dvsse_casnew', array('dvsse_id' => $dvsse->getId())),
            'form' => $form->createView(),
            'dvsse' => $dvsse
        ));
    }
}
