<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Produit;
use AppBundle\Entity\Article;
use AppBundle\Entity\Event;
use AppBundle\Entity\MpanisaVisitor;
use Symfony\Component\HttpFoundation\Session\Session;

class FrontController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $session = new Session();
        if(!$session->get('is_a_visitor')){
            $session->set('is_a_visitor','true');
            $em = $this->getDoctrine()->getManager();
            // $countryCode = $this->ip_info('Visitor','Country Code');
            $countryName = $this->ip_info('Visitor','Country');
            if($countryName){
                $repo = $em->getRepository('AppBundle:MpanisaVisitor');
                $xVfb = $repo->findOneBy(['countryCode'=> $countryName]);
                $counter = ($xVfb)?$xVfb:new MpanisaVisitor();
                $counter->setCountryCode($countryName);
                $counter->incrementNbVisitor();
                $em->persist($counter);
                $em->flush();
            }
        }
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
        // $next = $repoP->find($produit->getId()+1);
        // $prev = $repoP->find($produit->getId()+-1);
        $next = $repoP->getNextProduit($produit->getId());
        $prev = $repoP->getPreviousProduit($produit->getId());
        $haveNext =($next)?$next:false;
        $havePrev =($prev)?$prev:false;
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
        $em = $this->getDoctrine()->getManager();
        
        $other = $em->getRepository('AppBundle:Article')->getOtherArticle($article);
        return $this->render('front/articleDetails.html.twig',[
            'article'=> $article,'other'=>$other
            ]);
    }

    public function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            // $ip = $_SERVER["REMOTE_ADDR"];
            $ip = $this->container->get('request_stack')->getCurrentRequest()->getClientIp();
            // if ($deep_detect) {
            //     if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
            //         $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            //     if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
            //         $ip = $_SERVER['HTTP_CLIENT_IP'];
            // }
        }
        // $ip ="41.188.51.185";
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }

    


    

}
