<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Passenger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/adminarssam")
 */
class PassengerController extends Controller
{
    /**
     * Lists all passenger entities.
     *
     * @Route("/passager", name="arssam_passenger_list")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $passengers = $em->getRepository('AppBundle:Passenger')->findAll();

        return $this->render('@App/Passenger/index.html.twig',['passengers'=>$passengers]);
    }
}
