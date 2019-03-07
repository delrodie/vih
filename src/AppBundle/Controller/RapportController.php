<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Commentaire;
use AppBundle\Entity\Rapport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;

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
     * @Method({"POST","GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $roles[] = $user->getRoles();
        if (($roles[0][0] === 'ROLE_CALL') OR ($roles[0][0] === 'ROLE_SUP')){
            $gestionnaire = $em->getRepository("AppBundle:Gestionnaire")->findOneBy(['user'=>$user->getId()]);
            $rapports = $em->getRepository('AppBundle:Rapport')->findList($gestionnaire->getZone()->getId());
        }else{
            $rapports = $em->getRepository('AppBundle:Rapport')->findList();
        }

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
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser(); //
        $rapport = new Rapport();
        $form = $this->createForm('AppBundle\Form\RapportType', $rapport);
        $form->handleRequest($request); //die('ici cest pas bon');

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $gestionnaire = $em->getRepository('AppBundle:Gestionnaire')->findOneBy(['user'=>$user->getId()]);

            $date = $request->get('date'); //dump($date);die();
            $rapport->setStatut(1);
            $rapport->setDate($date);
            if ($gestionnaire->getZone()){
                $rapport->setZone($gestionnaire->getZone());
            }else{
                $zone = $em->getRepository("AppBundle:Zone")->findOneBy(['id'=>10]);
                $rapport->setZone($zone);
            }

            $em->persist($rapport);
            $em->flush();

            return $this->redirectToRoute('rapport_show', array('slug' => $rapport->getSlug()));
        }
        //$datedebut = new \DateTime("now -3 day", new \DateTimeZone('Africa/Abidjan'));
        $datefin = new \DateTime("now", new \DateTimeZone('Africa/Abidjan'));
        $datedebut = "2019-03-01";


        return $this->render('rapport/new.html.twig', array(
            'rapport' => $rapport,
            'form' => $form->createView(),
            'datedebut' => $datedebut,
            'datefin' => $datefin,
        ));
    }

    /**
     * Finds and displays a rapport entity.
     *
     * @Route("/{slug}", name="rapport_show")
     * @Method({"POST", "GET"})
     */
    public function showAction(Request $request, Rapport $rapport)
    {
        $deleteForm = $this->createDeleteForm($rapport);
        $em = $this->getDoctrine()->getManager();
        $commentaires = $em->getRepository('AppBundle:Commentaire')->findBy(['rapport'=>$rapport->getId()], ['id'=>'DESC']);

        return $this->render('rapport/show.html.twig', array(
            'rapport' => $rapport,
            'delete_form' => $deleteForm->createView(),
            'commentaires' => $commentaires,
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
