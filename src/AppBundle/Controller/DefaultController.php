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
    public function contactAction(Request $request){
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
           
            //Cherche si il y a une contenu mail dans la base64_decode
            $nameSender = $name;
			$sujetmail     = "Message from site web de: ".$nameSender;
            $contenumail   = $msg ;
            $from = $email;
			$mail = (new \Swift_Message('Upself mail'))
				->setFrom($from)
				->setTo($vmail)
				->setSubject($sujetmail)
				->setBody($contenumail)
				->setContentType('text/html');
			$this->get('mailer')->send($mail);
            $this->addFlash('info','Votre email a bien été envoyé');
      return $this->redirectToRoute('homepage');
            /** END SEND MAIL */
        /**  */
        // dump($rdv);die;

    }
}
