<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Statistique;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Statistique controller.
 *
 * @Route("statistiques")
 */
class StatistiqueController extends Controller
{
    /**
     * Lists all statistique entities.
     *
     * @Route("/", name="statistique_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $statistiques = $em->getRepository('AppBundle:Statistique')->findByDay();
        //dump($statistiques);die();

        return $this->render('statistique/index.html.twig', array(
            'statistiques' => $statistiques,
        ));
    }

    /**
     * Creates a new statistique entity.
     *
     * @Route("/new", name="statistique_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $statistique = new Statistique();
        $form = $this->createForm('AppBundle\Form\StatistiqueType', $statistique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $date = $request->get('date');
            $statistique->setDate($date);
            $em->persist($statistique);
            $em->flush();

            return $this->redirectToRoute('statistique_new');
        }
        $datedebut = new \DateTime("now -3 day", new \DateTimeZone('Africa/Abidjan'));
        $datefin = new \DateTime("now", new \DateTimeZone('Africa/Abidjan'));
        //$datedebut = "2019-03-01";

        return $this->render('statistique/new.html.twig', array(
            'statistique' => $statistique,
            'form' => $form->createView(),
            'datedebut' => $datedebut,
            'datefin' => $datefin
        ));
    }

    /**
     * Finds and displays a statistique entity.
     *
     * @Route("/{zone}", name="statistique_show")
     * @Method("GET")
     */
    public function showAction(Request $request, $zone)
    {
        $em = $this->getDoctrine()->getManager();
        $statistiques = $em->getRepository('AppBundle:Statistique')->findBy(['zone'=>$zone, 'statut'=>1]);
        $zone = $em->getRepository('AppBundle:Zone')->findOneBy(['id'=>$zone]);

        return $this->render('statistique/show.html.twig', array(
            'statistiques' => $statistiques,
            'zone' => $zone
        ));
    }

    /**
     * Displays a form to edit an existing statistique entity.
     *
     * @Route("/{id}/edit", name="statistique_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Statistique $statistique)
    {
        $deleteForm = $this->createDeleteForm($statistique);
        $editForm = $this->createForm('AppBundle\Form\StatistiqueType', $statistique);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('statistique_edit', array('id' => $statistique->getId()));
        }

        return $this->render('statistique/edit.html.twig', array(
            'statistique' => $statistique,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a statistique entity.
     *
     * @Route("/{id}", name="statistique_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Statistique $statistique)
    {
        $form = $this->createDeleteForm($statistique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($statistique);
            $em->flush();
        }

        return $this->redirectToRoute('statistique_index');
    }

    /**
     * Creates a form to delete a statistique entity.
     *
     * @param Statistique $statistique The statistique entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Statistique $statistique)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('statistique_delete', array('id' => $statistique->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
