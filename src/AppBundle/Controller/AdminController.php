<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/voaharyadmin")
 */
class AdminController extends Controller
{
   /**
     * @Route("/", name="voahary_admin_homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
       $em = $this->getDoctrine()->getManager();
       if($this->getUser()){
            $counter = $em->getRepository('AppBundle:MpanisaVisitor')->findAll();
            return $this->render('admin/index.html.twig',['counter'=>$counter]);
       }else{
            return $this->redirectToRoute('fos_user_security_login');
       }
        
    }

    /**
     * @Route("/filter", name="arssam_admin_filter",methods={"GET","POST"})
     */
    public function filterAction( Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        
        $status = ['0'=>['id'=>'1','name'=>'Traité'],'1'=>['id'=>'0','name'=>'Non traité']];  
        if($this->isGranted('ROLE_SUPER_ADMIN')){
            // $typologie = $em->getRepository('AppBundle:Typologie')->findAll();  
            $entite = $em->getRepository('AppBundle:Entite')->findAll();
            $typologie = $em->getRepository('AppBundle:Typologie')->findAllGroupByEntite();  
            // dump($typologie);die;
            return $this->render('admin/monitoring/filter-admin.html.twig', array(
                'status' => $status,
                'typologie' => $typologie,
                'entiteGroup' => $entite
            ));
        }elseif($this->isGranted('ROLE_FOCAL')){
            $entite = $em->getRepository('AppBundle:Entite')->findEntiteWithoutMe($user);    
            $entiteG = $em->getRepository('AppBundle:Entite')->findAll();
            $typologie = $em->getRepository('AppBundle:Typologie')->findAllGroupByEntite();  
            return $this->render('admin/monitoring/filter.html.twig', array(
                'status' => $status,
                'entite' => $entite,
                'typologie' => $typologie,
                'entiteGroup' => $entiteG
            ));
        }

    }
    public function filterLogAction( Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser(); 
        if($this->isGranted('ROLE_SUPER_ADMIN')){
            $entite = $em->getRepository('AppBundle:Entite')->findAll();    
            return $this->render('activitylog/filter-admin.html.twig', array(
                'entite' => $entite,
            ));
        }elseif($this->isGranted('ROLE_FOCAL')){ 
            return $this->render('activitylog/filter.html.twig');
        }

    }
    /**
     * @Route("/updatestat", name="arssam_admin_filter")
     */
    public function mandehaAction(){
        $em = $this->getDoctrine()->getManager();
        $msg = $em->getRepository('AppBundle:Message')->findAll();
        foreach($msg as $m){
            if($m->getReponse()){
                $m->setStatus(true);
                $em->flush($m);
            }
        }
        return $this->redirectToRoute('velirano_admin_homepage');
    }
    /**
     * @Route("/check-import",name="arssam_check_import")
     */
    public function checkImportDidAction(Request $request){
        $arr = ['PAF','DGD','GTA'];
        $em = $this->getDoctrine()->getManager();
        $eId = $request->request->get('entite');
        $date = new \DateTime('now');
        // dump($date);die;
        
        $filter['entiteId'] = $eId;
        $entite = $em->getRepository('AppBundle:Entite')->find($eId);
        if(in_array($entite->getAbreviation(),$arr)){
        $filter['dateDeb'] = \DateTime::createFromFormat('d/m/Y H:i:s',$date->format('d/m/Y').' 00:00:00');
        $filter['dateFin'] = \DateTime::createFromFormat('d/m/Y H:i:s',$date->format('d/m/Y').' 23:59:59');
        // dump($filter);die;
        $check = $em->getRepository('AppBundle:ActivityLog')->checkImportDid($filter);
        $result = ($check)?true:false;
        $res = ['result'=>$result];
        }else{
            $res = ['result'=>true]; 
        }
        return new JsonResponse($res);
    }
}
