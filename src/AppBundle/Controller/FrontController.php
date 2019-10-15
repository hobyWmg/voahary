<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Produit;
use AppBundle\Entity\Article;
use AppBundle\Entity\Event;

class FrontController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // return $this->redirectToRoute('fos_user_security_login');
        // replace this example code with whatever you need
        return $this->render('front/index.html.twig');
        // return $this->render('default/index.html.twig', [
        //     'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        // ]);
    }

    /**
     * @Route("/carousel", name="voahary_carousel")
     */
    public function carouselAction(){
        $em = $this->getDoctrine()->getManager();
        $carousels = $em->getRepository('AppBundle:Carousel')->getSlideByOrder();
        // dump($carousels);die;
        return $this->render('front/carousel.html.twig',['carousels'=>$carousels]);
    }

    /**
     * @Route("/produit", name="voahary_produits")
     */
    public function produitListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('AppBundle:Produit')->getProduitEnAvant();
        // dump($produits);die;
        return $this->render('front/produit/list.html.twig',['produits'=> $produits]);
    }

    /**
     * @Route("/produit/{id}", name="produit_details")
     */
    public function produitIndexAction(Request $request, Produit $produit)
    {
        $em = $this->getDoctrine()->getManager();
        $repoP = $em->getRepository('AppBundle:Produit');
        $next = $repoP->find($produit->getId()+1);
        $prev = $repoP->find($produit->getId()+-1);
        $haveNext =($next)?true:false;
        $havePrev =($prev)?true:false;
        return $this->render('front/produit/details.html.twig',['produit'=> $produit,'havePrev'=>$havePrev, 'haveNext'=> $haveNext]);
    }
    /**
     * @Route("/produit-all", name="voahary_all_produits", methods={"GET","POST"})
     */
    public function allProduitAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $param = [];
        if($request->getMethod()=="POST"){
            $word = $request->request->get('search');
            $param['search'] = $word;
            $produits = $em->getRepository('AppBundle:Produit')->getAllProduct($param);
            return $this->render('front/produit/list_ajax.html.twig',['produits'=> $produits]);
        }
        $produits = $em->getRepository('AppBundle:Produit')->getAllProduct($param);
        return $this->render('front/produit/all.html.twig',['produits'=> $produits]);
    }

    /**
     * @Route("/event", name="voahary_events")
     */
    public function eventAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('AppBundle:Event')->getAllEvent();
        // dump($produits);die;
        return $this->render('front/event/events.html.twig',['events'=> $events]);
    }
    /**
     * @Route("/event/{id}", name="voahary_event_voir")
     */
    public function eventDetailsAction(Request $request, Event $event)
    {
        return $this->render('front/eventDetails.html.twig',['event'=> $event]);
    }

    
    /**
     * @Route("/footer", name="voahary_events")
     */
    public function footerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $pm = $em->getRepository('AppBundle:Parametre')->findOneBy(['id'=>'1']);
        return $this->render('front/footer.html.twig',['param'=> $pm]);
    }

    /**
     * @Route("/article", name="voahary_events")
     */
    public function articleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ar = $em->getRepository('AppBundle:Article')->getOneArticle();
        // dump($ar);die;
        return $this->render('front/article.html.twig',['article'=> $ar[0]]);
    }

    /**
     * @Route("/article/{id}", name="voahary_article_lire")
     */
    public function articleDetailsAction(Request $request, Article $article)
    {
        return $this->render('front/articleDetails.html.twig',['article'=> $article]);
    }

    


    

}
