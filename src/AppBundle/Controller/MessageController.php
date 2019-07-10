<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\MessageType;
use AppBundle\Form\CMessageType;
use AppBundle\Entity\Message;
use AppBundle\Services\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/adminarssam")
 */
class MessageController extends Controller
{
   /**
     * @Route("/message", name="arssam_message_index")
     */
    public function indexAction(Request $request, Log $actLog)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $log = [];
        $repoMessage = $em->getRepository('AppBundle:Message');
        $query = $repoMessage->getAllRecivedMessage($user);
        $paginator  = $this->get('knp_paginator');
        $messages = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),5
        );
       
        $messagesUnread = $repoMessage->getUnreadMessage($user);
        $newMessage = new Message();
        $form = $this->createForm(CMessageType::class,$newMessage,['user' => $this->getUser()]);
        $form->handleRequest($request);
        if ($form->isSubmitted() ) {
            if($form->isValid()){
                // dump($form->getData());die;
                $newMessage->setUserSender($user); 
                $sujet = $newMessage->getTypologie()->getSujet();
                $newMessage->setSujet($sujet);
                $em->persist($newMessage);
                $em->flush();  
                $this->addFlash('success','Message envoyé');
                /** Add Log */
                // $log['user'] = $user;
                // $log['action'] = 'Nouvelle demande';
                // $log['description'] = 'Demande '.$newMessage->getTypologie()->getSujet();
                // $log['entityId'] = $newMessage->getId();
                // $log['entityName'] = 'Message';
                // $actLog->addLog($log);
                /** endLog */
                return $this->redirectToRoute('arssam_message_index');
            }else{
                // dump($form->getErrors());die;
            }
        }

        return $this->render('@App/Message/index.html.twig',
        ['messages'=> $messages,
          'form'=> $form->createView(),
          'nbmsg' => count($messagesUnread)
        ]);
    }

     /**
     * @Route("/message/sent", name="arssam_message_sent")
     */
    public function sentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $log = [];
        $repoMessage = $em->getRepository('AppBundle:Message');
        $query = $repoMessage->getAllSentMessage($user);
        $paginator  = $this->get('knp_paginator');
        $messages = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),10
        );
       
        $messagesUnread = $repoMessage->getUnreadMessage($user);
        $newMessage = new Message();
        // $form = $this->createForm(CMessageType::class,$newMessage);
        $form = $this->createForm(CMessageType::class,$newMessage,['user' => $this->getUser()]);
        $form->handleRequest($request);
        return $this->render('@App/Message/sent.html.twig',
        ['messages'=> $messages,
          'form'=> $form->createView(),
          'nbmsg' => count($messagesUnread)
        ]);
    }

     /**
     * @Route("/message/show/{id}", name="arssam_message_show")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository('AppBundle:Message')->find($id);
        $parentMsg = $message->getParentMessage();
        $newMessage = new Message();
        $formR = $this->createForm(MessageType::class,$newMessage);
        $form = $this->createForm(CMessageType::class,$newMessage,['user' => $this->getUser()]);
        if(!$this->isGranted('ROLE_SUPER_ADMIN')){
            $message->setVue(true);
            if($message->getParentMessage()){
                $message->setStatus(true);
            }
            $em->flush($message);
        }
        return $this->render('@App/Message/details.html.twig',
        ['messages'=> $message,
        'parentMsg'=> $parentMsg,
        'form' => $form->createView(),
        'formR' => $formR->createView()
        ]);
       
    }

    /**
     * @Route("/message/show-sent/{id}", name="arssam_message_sent_show")
     */
    public function showMsgSentAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository('AppBundle:Message')->find($id);
        $parentMsg = $message->getParentMessage();
        $newMessage = new Message();
        $formR = $this->createForm(MessageType::class,$newMessage);
        $form = $this->createForm(CMessageType::class,$newMessage,['user' => $this->getUser()]);
        return $this->render('@App/Message/detailsSent.html.twig',
        ['messages'=> $message,
        'parentMsg'=> $parentMsg,
        'form' => $form->createView(),
        'formR' => $formR->createView()
        ]);
       
    }

     /**
     * @Route("/message/reply/{id}", name="arssam_message_reply")
     */
    public function replyAction($id,Request $request, Log $actLog)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $message = $em->getRepository('AppBundle:Message')->find($id);
        $reponse = new Message();
        $formR = $this->createForm(MessageType::class,$reponse);
        $formR->handleRequest($request);
        if ($formR->isSubmitted() && $formR->isValid()) {
           $message->setStatus(true);
           $entiteReceiver = $message->getUserSender()->getEntite();
           $reponse->setUserSender($user); 
           $reponse->setEntiteReceiver($entiteReceiver);
           $message->setReponse($reponse);
        //    if($formR->getData()->getDocument()->getFile()){
        //        $document = $formR->getData()->getDocument();
        //        $em->persist($document);
        //    }
           $sujet = $message->getTypologie()->getSujet();
           $reponse->setSujet('Re:'.$sujet);
           $em->persist($message);
           $em->persist($reponse);
           $em->flush();  
           /** Add Log */
        //    $log['user'] = $user;
        //    $log['action'] = 'Reponse a une demande';
        //    $log['description'] = 'Reponse '.$reponse->getSujet();
        //    $log['entityId'] = $reponse->getId();
        //    $log['entityName'] = 'Message';
        //    $actLog->addLog($log);
           /** endLog */
           $this->addFlash('success','Message envoyé');
           return $this->redirectToRoute('arssam_message_index');
        }
        return $this->render('@App/Message/details.html.twig',
        ['messages'=> $message,
        'formR' => $formR->createView()
        ]);
       
    }

     /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * Action suppression des notifications
     *
     * @Route("/message/update-display", name="arssam_update_display", options={"expose"=true})
     * @Method("POST")
     */
    public function updateDisplayAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (is_null($user)){
            return $this->redirectToRoute('index');
        }
        $messageUnread = $em->getRepository('AppBundle:Message')->getUnreadMessage($user);
        /*foreach ($messageUnread as $key => $message) {
            if($message->getParentMessage() && $message->getVue()== true){
                unset($messageUnread[$key]);
            }
        }*/
        return $this->render('@App/Message/_notificationMessage.html.twig', array(
            'countMessagesUnreadByUser' => count($messageUnread),
            'messagesUnreadByUser' => $messageUnread,
            'user' => $user
        ));
    }

    /**
     * Action group
     *
     * @Route(
     *     "/messages-group-action/{action}",
     *     name="messages_action_group",
     *     requirements={"action" = "delete"},
     *     options={"expose"=true}
     * )
     * @Method({"POST"})
     *
     * @param  Request $request request
     * @param  string  $action  delete
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function actionGroupAction(Request $request, $action)
    {
        $em             = $this->getDoctrine()->getManager();
        $successMessage = array(
            'delete' => 'Les messages sélectionnés ont été supprimés',
        );
        $ids = $request->request->get('element');

        if (count($ids)) {
            $this->updateActionByGroup($ids, $em, $action, $successMessage[$action]);
            $this->get('session')->getFlashBag()->add('success', $successMessage[$action]);
        } else {
            $this->get('session')->getFlashBag()->add('warning', 'Vous n\'avez sélectionné aucun message');
        }

        return $this->redirect($this->generateUrl('arssam_message_index'));
    }

    /**
     * Method pour switcher les actions
     * @param array                       $ids
     * @param \Doctrine\ORM\EntityManager $em
     * @param string                      $action
     */
    public function updateActionByGroup($ids, $em, $action)
    {
        $errorMessage = array();
        foreach ($ids as $id) {
            $message         = $em->getRepository('AppBundle:Message')->find($id);
            if ($action == 'delete') {
                $em->remove($message);
            }
            $em->flush();
            $em->clear();
        }
        if (count($errorMessage) > 0) {
            $this->get('session')->getFlashBag()->add(
                'error',
                'Désolé, certain message n\'a pas pu être effacé.'
            );
        }
    }

    /**
     *
     * @Route("/gettypo", name="entite_list_typologie", methods={"GET"})
     */

    public function getTypologieByEntite(Request $request){
       $em = $this->getDoctrine()->getManager();
       $entityId = $request->query->get("entityId");
       
       $typologies = $em->getRepository('AppBundle:Typologie')->getTypologieByEntite($entityId);
       $responseArray = array();
       foreach($typologies as $typologie){
           $responseArray[] = array(
               "id" => $typologie->getId(),
               "name" => $typologie->getSujet()
           );
       }
       return new JsonResponse($responseArray);
    }
}
