<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Rapport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Rapport controller.
 *
 * @Route("rapport")
 */
class RapportController extends Controller
{
    /**
     * Lists all rapport entities.
     *
     * @Route("/", name="rapport_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rapports = $em->getRepository('AppBundle:Rapport')->findList();

        return $this->render('rapport/index.html.twig', array(
            'rapports' => $rapports,
        ));
    }

    /**
     * Creates a new rapport entity.
     *
     * @Route("/new", name="rapport_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $rapport = new Rapport();
        $form = $this->createForm('AppBundle\Form\RapportType', $rapport);
        $form->handleRequest($request); //die('ici cest pas bon');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $mois = $request->get('mois');
            $jour = $request->get('jour');
            $date = '2019'.'-'.$mois.'-'.$jour; //dump($date);die();
            $rapport->setStatut(1);
            $rapport->setDate($date);
            $em->persist($rapport);
            $em->flush();

            return $this->redirectToRoute('rapport_show', array('slug' => $rapport->getSlug()));
        }

        return $this->render('rapport/new.html.twig', array(
            'rapport' => $rapport,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a rapport entity.
     *
     * @Route("/{slug}", name="rapport_show")
     * @Method("GET")
     */
    public function showAction(Rapport $rapport)
    {
        $deleteForm = $this->createDeleteForm($rapport);

        return $this->render('rapport/show.html.twig', array(
            'rapport' => $rapport,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing rapport entity.
     *
     * @Route("/{id}/edit", name="rapport_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Rapport $rapport)
    {
        $deleteForm = $this->createDeleteForm($rapport);
        $editForm = $this->createForm('AppBundle\Form\RapportType', $rapport);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rapport_edit', array('id' => $rapport->getId()));
        }

        return $this->render('rapport/edit.html.twig', array(
            'rapport' => $rapport,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a rapport entity.
     *
     * @Route("/{id}", name="rapport_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Rapport $rapport)
    {
        $form = $this->createDeleteForm($rapport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rapport);
            $em->flush();
        }

        return $this->redirectToRoute('rapport_index');
    }

    /**
     * Creates a form to delete a rapport entity.
     *
     * @param Rapport $rapport The rapport entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Rapport $rapport)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rapport_delete', array('id' => $rapport->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
