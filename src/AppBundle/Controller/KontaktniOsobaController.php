<?php

namespace AppBundle\Controller;

use AppBundle\Entity\KontaktniOsoba;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Kontaktniosoba controller.
 *
 * @Route("kontaktniosoba")
 */
class KontaktniOsobaController extends Controller
{
    /**
     * Lists all kontaktniOsoba entities.
     *
     * @Route("/", name="kontaktniosoba_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $kontaktniOsobas = $em->getRepository('AppBundle:KontaktniOsoba')->findAll();

        return $this->render('kontaktniosoba/index.html.twig', array(
            'kontaktniOsobas' => $kontaktniOsobas,
        ));
    }







    /**
     * Creates a new kontaktniOsoba entity.
     *
     * @Route("/new", name="kontaktniosoba_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $kontaktniOsoba = new Kontaktniosoba();
        $form = $this->createForm('AppBundle\Form\KontaktniOsobaType', $kontaktniOsoba);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($kontaktniOsoba);
            $em->flush();

            return $this->redirectToRoute('kontaktniosoba_show', array('id' => $kontaktniOsoba->getId()));
        }

        return $this->render('kontaktniosoba/new.html.twig', array(
            'kontaktniOsoba' => $kontaktniOsoba,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a kontaktniOsoba entity.
     *
     * @Route("/{id}", name="kontaktniosoba_show")
     * @Method("GET")
     */
    public function showAction(KontaktniOsoba $kontaktniOsoba)
    {
        $deleteForm = $this->createDeleteForm($kontaktniOsoba);

        return $this->render('kontaktniosoba/show.html.twig', array(
            'kontaktniOsoba' => $kontaktniOsoba,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing kontaktniOsoba entity.
     *
     * @Route("/{id}/edit", name="kontaktniosoba_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, KontaktniOsoba $kontaktniOsoba)
    {
        $deleteForm = $this->createDeleteForm($kontaktniOsoba);
        $editForm = $this->createForm('AppBundle\Form\KontaktniOsobaType', $kontaktniOsoba);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('kontaktniosoba_edit', array('id' => $kontaktniOsoba->getId()));
        }

        return $this->render('kontaktniosoba/edit.html.twig', array(
            'kontaktniOsoba' => $kontaktniOsoba,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a kontaktniOsoba entity.
     *
     * @Route("/{id}", name="kontaktniosoba_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, KontaktniOsoba $kontaktniOsoba)
    {
        $form = $this->createDeleteForm($kontaktniOsoba);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($kontaktniOsoba);
            $em->flush();
        }

        return $this->redirectToRoute('kontaktniosoba_index');
    }

    /**
     * Creates a form to delete a kontaktniOsoba entity.
     *
     * @param KontaktniOsoba $kontaktniOsoba The kontaktniOsoba entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(KontaktniOsoba $kontaktniOsoba)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('kontaktniosoba_delete', array('id' => $kontaktniOsoba->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
