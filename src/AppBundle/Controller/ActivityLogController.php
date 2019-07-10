<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ActivityLog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Activitylog controller.
 *
 * @Route("adminarssam/activitylog")
 */
class ActivityLogController extends Controller
{
    /**
     * Lists all activityLog entities.
     *
     * @Route("/", name="adminarssam_activitylog_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $user = $this->getUser();
        $filters = array();
        if($user->getEntite()){
           $filters['entite'] = $user->getEntite()->getId(); 
        }
        if ($request->getMethod() === 'POST'){
            $filters['dateDeb'] = (trim($request->get('dateDeb')) != "")? \DateTime::createFromFormat('d/m/Y H:i:s',$request->get('dateDeb').' 00:00:00'):"";
            $filters['dateFin'] = (trim($request->get('dateDeb')) != "")? \DateTime::createFromFormat('d/m/Y H:i:s',$request->get('dateDeb').' 23:59:59'):"";
            if($request->get('entite')){
            $filters['entite'] = $request->get('entite');
            }
            $session->set('filter-log',$filters);
        }
        $activityLogsQuery = $em->getRepository('AppBundle:ActivityLog')->findActivityByFilter($filters);
        $paginator  = $this->get('knp_paginator');
        $activityLogs = $paginator->paginate(
            $activityLogsQuery, 
            $request->query->getInt('page', 1),20
        );
        return $this->render('activitylog/index.html.twig', array(
            'activityLogs' => $activityLogs,
        ));
    }

    /**
     * Finds and displays a activityLog entity.
     *
     * @Route("/{id}", name="adminarssam_activitylog_show")
     * @Method("GET")
     */
    public function showAction(ActivityLog $activityLog)
    {

        return $this->render('activitylog/show.html.twig', array(
            'activityLog' => $activityLog,
        ));
    }
}
