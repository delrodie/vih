<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Callcenter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Callcenter controller.
 *
 * @Route("callcenter")
 */
class CallcenterController extends Controller
{
    /**
     * Lists all callcenter entities.
     *
     * @Route("/", name="callcenter_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $callcenters = $em->getRepository('AppBundle:Callcenter')->findList(); //dump($callcenters);die();

        return $this->render('callcenter/index.html.twig', array(
            'callcenters' => $callcenters,
        ));
    }

    /**
     * Creates a new callcenter entity.
     *
     * @Route("/new", name="callcenter_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $callcenter = new Callcenter();
        $form = $this->createForm('AppBundle\Form\CallcenterType', $callcenter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $statut = $request->get('statut');
            $date = date('Y-m-d', time());
            $callcenter->setStatut($statut);
            $callcenter->setDate($date);
            $em->persist($callcenter);
            $em->flush();

            return $this->redirectToRoute('callcenter_show', array('slug' => $callcenter->getSlug()));
        }

        return $this->render('callcenter/new.html.twig', array(
            'callcenter' => $callcenter,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a callcenter entity.
     *
     * @Route("/{slug}", name="callcenter_show")
     * @Method("GET")
     */
    public function showAction(Callcenter $callcenter)
    {
        $deleteForm = $this->createDeleteForm($callcenter);

        return $this->render('callcenter/show.html.twig', array(
            'callcenter' => $callcenter,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing callcenter entity.
     *
     * @Route("/{id}/edit", name="callcenter_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Callcenter $callcenter)
    {
        $deleteForm = $this->createDeleteForm($callcenter);
        $editForm = $this->createForm('AppBundle\Form\CallcenterType', $callcenter);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('callcenter_edit', array('id' => $callcenter->getId()));
        }

        return $this->render('callcenter/edit.html.twig', array(
            'callcenter' => $callcenter,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a callcenter entity.
     *
     * @Route("/{id}", name="callcenter_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Callcenter $callcenter)
    {
        $form = $this->createDeleteForm($callcenter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($callcenter);
            $em->flush();
        }

        return $this->redirectToRoute('callcenter_index');
    }

    /**
     * Creates a form to delete a callcenter entity.
     *
     * @param Callcenter $callcenter The callcenter entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Callcenter $callcenter)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('callcenter_delete', array('id' => $callcenter->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
