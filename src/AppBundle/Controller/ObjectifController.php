<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class ObjectifController
 * @Route("objectif")
 */
class ObjectifController extends Controller
{
    /**
     * @Route("/", name="objectif_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $roles[] = $user->getRoles();
        if ($roles[0][0] === 'ROLE_SUP'){
            $gestionnaire = $em->getRepository("AppBundle:Gestionnaire")->findOneBy(['user'=>$user->getId()]); //dump($gestionnaire->getZone()->getSlug());die();
            $atteint = $em->getRepository('AppBundle:Statistique')->objectifAction($gestionnaire->getZone()->getSlug());
            $objectif = $em->getRepository('AppBundle:Zone')->findOneBy(['id'=>$gestionnaire->getZone()->getId()])->getObjectif(); //dump($objectif);die();
        }else{
            $atteint = $em->getRepository('AppBundle:Statistique')->objectifAction();
            $objectif = $em->getRepository('AppBundle:Zone')->findStatitiqueGlobal();
        }

        return $this->render('default/diagramme.html.twig',[
            'objectif' => $objectif,
            'atteint' => $atteint
        ]);
    }
}
