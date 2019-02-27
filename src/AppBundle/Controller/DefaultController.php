<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("rapport", name="rapport")
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
        return $this->render('default/journal.html.twig');
    }
}
