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
}
