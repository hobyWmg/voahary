<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Form\UserTypeEdit;
use AppBundle\Services\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/adminarssam")
 */
class UserController extends Controller
{
    /**
     * @Route("/user", name="arssam_user_list")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $search = [];
        $user = $this->getUser();
        
        if(!$user->hasRole('ROLE_SUPER_ADMIN')){
            $entite = $user->getEntite();
            $search['entiteId'] = $entite->getId();
        }
        if($user->hasRole('ROLE_OFFICIER')){
            $search['onlyMe'] = $user->getId();
        }
        // $users = $em->getRepository('AppBundle:User')->getUserByFilters($search);
        $users = $em->getRepository('AppBundle:User')->getQueryUserByFilters($search);
        // $paginator  = $this->get('knp_paginator');
        // $users = $paginator->paginate(
        //     $query, 
        //     $request->query->getInt('page', 1),2
        // );
        return $this->render('@App/User/index.html.twig',['users'=>$users]);
    }

    /**
     * @Route("/user/add", name="arssam_user_create")
     */
    public function createAction(Request $request, Log $actLog)
    {
        $userConected = $this->getUser();
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $data = $request->request->get('appbundle_user');
           $user->addRole($data['roles']);
           /** create username */
           $name = $data['lastname'];
           $firstname = $data['firstname']; 
        //    $username = $firstname[0].$firstname[strlen($firstname)-1].".".$name;
           $username = $firstname[0].$firstname[1].".".$name;
           $user->setUsername($username);
           /** end */
           /** assign role & entite */
           if($userConected->hasRole('ROLE_FOCAL')){
               $user->setEntite($userConected->getEntite());
               $user->setRoles(['ROLE_OFFICIER']);
           }
           /** end */
           $em->persist($user);
           $em->flush();  
           /** ADD LOG */
        //    $log = [];
        //    $log['user'] = $userConected;
        //    $log['action'] = 'CREATE';
        //    $log['description'] = 'Nouvelle utilisateur';
        //    $log['entityId'] = $user->getId();
        //    $log['entityName'] = 'user';
        //    $actLog->addLog($log);
           /** end  */  
        $this->addFlash('success','Opération effectuée avec succès');
        return $this->redirectToRoute('arssam_user_list');
        }
        return $this->render('AppBundle:User:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/user/edit/{id}", name="arssam_user_edit")
     */
    public function updateAction($id,Request $request, Log $actLog)
    {
        $userConected = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);
        $form = $this->createForm(UserTypeEdit::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $role = $request->request->get('role');
           $data = $request->request->get('appbundle_user_edit');
            /** create username */
            $name = $data['lastname'];
            $firstname = $data['firstname']; 
            // $username = $firstname[0].$firstname[strlen($firstname)-1].".".$name;
            $username = $firstname[0].$firstname[1].".".$name;
            $user->setUsername($username);           
           $user->setRoles([$role]);
           $em->persist($user);
           $em->flush();   
           /** ADD LOG */
        //    $log = [];
        //    $log['user'] = $userConected;
        //    $log['action'] = 'UPDATE';
        //    $log['description'] = 'Modification utilisateur';
        //    $log['entityId'] = $user->getId();
        //    $log['entityName'] = 'user';
        //    $actLog->addLog($log);
           /** end  */  
           $this->addFlash('success','Opération effectuée avec succès');   
           return $this->redirectToRoute('arssam_user_list');
        }
        $listRoles = ['ROLE_SUPER_ADMIN'=> 'Super Admin','ROLE_FOCAL'=> 'Point focal', 'ROLE_OFFICIER'=>'Officier de liaison'];
        return $this->render('AppBundle:User:update.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
            'listRoles' => $listRoles
        ));
    }
    /**
     * @Route("/user/delete/{id}", name="arssam_user_delete")
     */
    public function deleteAction($id,Request $request, Log $actLog)
    {
        $em = $this->getDoctrine()->getManager();
        $userConected = $this->getUser();
        $user = $em->getRepository('AppBundle:User')->find($id);
        /** ADD LOG */
        // $log = [];
        // $log['user'] = $userConected;
        // $log['action'] = 'DELETE';
        // $log['description'] = 'Suppression/Désactivation utilisateur '.$user->getFirstname().' '.$user->getLastname();
        // $log['entityId'] = $user->getId();
        // $log['entityName'] = 'user';
        // $actLog->addLog($log);
        /** end  */ 
        $user->setEnabled(false);
        $em->flush();
        $this->addFlash('success','Opération effectuée avec succès');
        return $this->redirectToRoute('arssam_user_list');
        
    }

    /**
     * @Route("/user/reactive/{id}", name="arssam_user_reactive")
     */
    public function reactiveAction($id,Request $request, Log $actLog)
    {
        $em = $this->getDoctrine()->getManager();
        $userConected = $this->getUser();
        $user = $em->getRepository('AppBundle:User')->find($id);
        /** ADD LOG */
        // $log = [];
        // $log['user'] = $userConected;
        // $log['action'] = 'DELETE';
        // $log['description'] = 'Suppression/Désactivation utilisateur '.$user->getFirstname().' '.$user->getLastname();
        // $log['entityId'] = $user->getId();
        // $log['entityName'] = 'user';
        // $actLog->addLog($log);
        /** end  */ 
        $user->setEnabled(true);
        $em->flush();
        $this->addFlash('success','Opération effectuée avec succès');
        return $this->redirectToRoute('arssam_user_list');
        
    }
    /**
     * @Route ("/user/delete-photo", name="arssam_user_delete_photo")
     * */
    public function deletePhotoAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('userId');
        $user = $em->getRepository('AppBundle:User')->find($id);
        $user->setPhoto(null);
        $em->flush();
        return new JsonResponse(['success'=>true]);
    }
}
