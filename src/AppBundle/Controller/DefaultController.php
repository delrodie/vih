<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("menu", name="menu")
     */
    public function menuAction()
    {
        return $this->render("default/menu.html.twig");
    }

    /**
     * @Route("rapport", name="rapports")
     */
    public function rapportAction()
    {
        return $this->render('default/rapport.html.twig');
    }

    /**
     * @Route("journal", name="journal")
     */
    public function journalAction()
    {
        return $this->render('default/statistique.html.twig');
    }

    /**
     * @Route("observations", name="observation")
     */
    public function observationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rapports = $em->getRepository('AppBundle:Rapport')->findAll();

        return $this->render('default/rapport_popup.html.twig',[
            'rapports' => $rapports
        ]);
    }

    /**
     * @Route("zones-modification", name="zone_modification")
     */
    public function zoneAction()
    {
        $em = $this->getDoctrine()->getManager();
        $zones = $em->getRepository('AppBundle:Zone')->findAll();

        return $this->render('default/zone_popup.html.twig',[
            'zones' => $zones
        ]);
    }

    /**
     * @Route("messages", name="messages")
     */
    public function messageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $roles[] = $user->getRoles();
        if ($roles[0][0] === 'ROLE_SUP'){
            $gestionnaire = $em->getRepository("AppBundle:Gestionnaire")->findOneBy(['user'=>$user->getId()]);
            $cptCommentaire = $em->getRepository('AppBundle:Commentaire')->compteur($gestionnaire->getZone()->getId());
            //die('ici');
        }else{
            $cptCommentaire = null;
        }

        return $this->render('default/message.html.twig',[
            'compteur'=>$cptCommentaire
        ]);
    }

    /**
     * @Route("/diagramme-principal", name="diagramme_principal")
     */
    public function diagrammeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $total = $em->getRepository('AppBundle:Zone')->findStatitiqueGlobal();
        $objectif = $em->getRepository('AppBundle:Statistique')->objectifAction();
        //dump($total);die();
        return $this->render('default/diagramme_principal.html.twig',[
            'total' => $total,
            'objectif' => $objectif
        ]);
    }

    /**
     * @Route("/histogramme{slug}", name="histogramme_principal")
     * @Method("GET")
     */
    public function histogrammeAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $objectif = $em->getRepository('AppBundle:Statistique')->objectifAction($slug);
        return $this->render('default/objectif.html.twig',[
            'objectif' => $objectif
        ]);
    }

    /**
     * @Route("{date}/call-center", name="callcenter")
     * @Method("GET")
     */
    public function callAction($date)
    {
        $em = $this->getDoctrine()->getManager();
        $calls = $em->getRepository('AppBundle:Callcenter')->findBy(['date'=>$date]);
        return $this->render('callcenter/calls.html.twig',[
            'date' => $date,
            'calls' => $calls,
        ]);
    }

    /**
     * @Route("/statistiques/{date}", name="statistique")
     * @Method("GET")
     */
    public function statistiqueAction($date)
    {
        $em = $this->getDoctrine()->getManager();
        $statistiques = $em->getRepository('AppBundle:Statistique')->findBy(['date'=>$date, 'statut'=>1]);
        return $this->render('statistique/tableau.html.twig',[
            'date' => $date,
            'statistiques' => $statistiques
        ]);
    }
}
