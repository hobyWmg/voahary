<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{

    /**
     * @Route("/contact", name="voahary_contact", methods={"GET","POST"})
     */
    public function contactAction(Request $request,\Swift_Mailer $mailer){
        $em = $this->getDoctrine()->getManager();
        $repoParameter = $em->getRepository('AppBundle:Parametre');
        $parameter = $repoParameter->findAll();
        $data = $request->request;
        $name = $data->get('name');
        $email = $data->get('email');
        $msg = $data->get('message');
        $vmail = $parameter[0]->getEmail();
                 /** SEND MAIL */

            date_default_timezone_set('UTC');
            setlocale(LC_ALL, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');
            $nameSender = $name;
			$sujetmail     = "Message from site web de: ".$nameSender;
            $contenumail   = $msg ;
            $from = $email;
			$mail = (new \Swift_Message('Hello Email'))
				->setFrom($from)
				->setTo($vmail)
				->setSubject($sujetmail)
				->setBody($contenumail);
				//->setContentType('text/html');
               $t =  $mailer->send($mail);
            //    dump($t);die;
            // $this->addFlash('info','Votre email a bien été envoyé');
      return $this->redirectToRoute('homepage');
            /** END SEND MAIL */
        /**  */
        // dump($rdv);die;

    }
}
