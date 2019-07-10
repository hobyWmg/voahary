<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/adminarssam/monitoring")
 */
class MonitoringController extends Controller
{
   /**
     * @Route("/msgSent", name="arssam_monitoring_sent")
     */
    public function getMsgSentAction(Request $request,$entite)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $filter = $session->get('filter');
        // $entite = $this->getUser()->getEntite();
        $filter['entiteUser'] = $entite;
        // dump($filter);die;
        $sentQuery = $em->getRepository('AppBundle:Message')->getAllSentDemandeByFilter($filter);
        $paginator  = $this->get('knp_paginator');
        $sent = $paginator->paginate(
            $sentQuery, 
            $request->query->getInt('page', 1),5
        );
        return $this->render('admin/monitoring/sent.html.twig',['sent'=> $sent,'nb'=> $sent->getTotalItemCount()]);
    }
    /**
     * @param  Request $request
     * @return Response
     * @Route("/msgSent-server", name="arssam_monitoring_sent_ss")
     */
    public function getMsgSentSsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $pagenum = 1;
		$nbperpage = 5;
        $session = $request->getSession();
        $filter = $session->get('filter');
        if($request->query->get('start') != ''){
			$pagenum = $request->query->get('start');
		}
		if($request->query->get('length') != ''){
			$nbperpage = $request->query->get('length');
        }
        $id = $request->query->get('entiteId');
        $entite = $em->getRepository('AppBundle:Entite')->find($id);
        $filter['entiteUser'] = $entite;
        // $sentQuery = $em->getRepository('AppBundle:Message')->getAllSentDemandeByFilter($filter);
        $sent = $em->getRepository('AppBundle:Message')->getAllSentDemandeByFilter($pagenum,$nbperpage,$filter);
        // $paginator  = $this->get('knp_paginator');
        // $sent = $paginator->paginate(
        //     $sentQuery, 
        //     $request->query->getInt('page', 1),5
        // );
        $result = [];
        $result['draw'] = $request->query->get('draw');
		$result['recordsTotal'] = $sent['total'];
		$result['recordsFiltered'] = $sent['total'];
        $result['data'] = array();
        foreach($sent['result'] as $key => $value){
            $class='label-danger';
            $status = 'Non traité';
            if($value->getStatus()){
                $class='label-success';
                $status = 'Traité';
            }
            $result['data'][] = array(	$value->getEntiteReceiver()->getAbreviation(),
                                        '<a href="'.$this->generateUrl('arssam_message_sent_show',['id'=>$value->getId()]).'">'.substr($value->getSujet(), 0, 20)."...".'</a>',
										$value->getCreatedAt()->format('d/m/Y H:i'),
										'<span class="label '.$class.'">'.$status.'</span>'
										
								);
		}
        // return $this->render('admin/monitoring/sent.html.twig',['sent'=> $sent,'nb'=> $sent->getTotalItemCount()]);
        return new JsonResponse($result);
    }

    /**
     * @Route("/msgReceived", name="arssam_monitoring_received")
     */
    public function getMsgReceivedAction(Request $request,$entite)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $filter = $session->get('filter');
        // $entite = $this->getUser()->getEntite();
        $filter['entiteUser'] = $entite;
        $receivedQuery = $em->getRepository('AppBundle:Message')->getAllRecivedDemandeByFilter($filter);
        
        $paginator  = $this->get('knp_paginator');
    
        $received = $paginator->paginate(
            $receivedQuery, 
            $request->query->getInt('page', 1),5
        );
        return $this->render('admin/monitoring/received.html.twig',['received'=>$received,'nb'=> $received->getTotalItemCount()]);
    }
    /**
     * @param  Request $request
     * @return Response
     * @Route("/msgReceived-server", name="arssam_monitoring_received_ss")
     */
    public function getMsgReceivedSsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $pagenum = 1;
		$nbperpage = 5;
        $session = $request->getSession();
        $filter = $session->get('filter');
        if($request->query->get('start') != ''){
			$pagenum = $request->query->get('start');
		}
		if($request->query->get('length') != ''){
			$nbperpage = $request->query->get('length');
        }
        $id = $request->query->get('entiteId');
        $entite = $em->getRepository('AppBundle:Entite')->find($id);
        $filter['entiteUser'] = $entite;
        $received = $em->getRepository('AppBundle:Message')->getAllRecivedDemandeByFilter($pagenum,$nbperpage,$filter);
        $result = [];
        $result['draw'] = $request->query->get('draw');
		$result['recordsTotal'] = $received['total'];
		$result['recordsFiltered'] = $received['total'];
        $result['data'] = array();
        // dump($received);die;
        foreach($received['result'] as $key => $value){
            $class='label-danger';
            $status = 'Non traité';
            if($value->getStatus()){
                $class='label-success';
                $status = 'Traité';
            }
            $result['data'][] = array(	$value->getuserSender()->getEntite()->getAbreviation(),
                                        '<a href="'.$this->generateUrl('arssam_message_show',['id'=>$value->getId()]).'">'.substr($value->getSujet(), 0, 20)."...".'</a>',
										$value->getCreatedAt()->format('d/m/Y H:i'),
										'<span class="label '.$class.'">'.$status.'</span>'
										
								);
		}
    
        // $received = $paginator->paginate(
        //     $receivedQuery, 
        //     $request->query->getInt('page', 1),5
        // );
        // return $this->render('admin/monitoring/received.html.twig',$result);
        return new JsonResponse($result);
    }

    public function getMonitoring(){

    }
}
