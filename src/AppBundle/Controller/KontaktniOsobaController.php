<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Firma;
use AppBundle\Entity\KontaktniOsoba;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

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
     * @Route("/index/{id}", name="kontaktniosoba_index")
     * @Method("GET")
     */
    public function indexAction(Firma $id)
    {
        $em = $this->getDoctrine()->getManager();

        $kontaktniOsobas = $em->getRepository('AppBundle:KontaktniOsoba')->findBy(array('firma' => $id));
    //$kontaktniOsobas = $em->getRepository('AppBundle:KontaktniOsoba')->findAll();
        return $this->render('kontaktniosoba/index.html.twig', array(
            'kontaktniOsobas' => $kontaktniOsobas,
            'firma' => $id,
        ));
    }


    /**
     * Creates a new kontaktniOsoba entity.
     *
     * @Route("/new/{firma}", name="kontaktniosoba_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Firma $firma, Request $request)
    {
        $kontaktniOsoba = new Kontaktniosoba();
        $kontaktniOsoba->setFirma($firma);
        $form = $this->createForm('AppBundle\Form\KontaktniOsobaType', $kontaktniOsoba);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($kontaktniOsoba);
            $em->flush();

            return $this->redirectToRoute('kontaktniosoba_index', array('id'=>$firma->getId()));
            //return $this->redirectToRoute('kontaktniosoba_show', array('id' => $kontaktniOsoba->getId()));
        }

        return $this->render('kontaktniosoba/new.html.twig', array(
            'kontaktniOsoba' => $kontaktniOsoba,
            'firma' => $firma,
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
    public function editAction(Request $request, KontaktniOsoba $kontaktniOsoba )
    {
        $deleteForm = $this->createDeleteForm($kontaktniOsoba);
        $editForm = $this->createForm('AppBundle\Form\KontaktniOsobaType', $kontaktniOsoba);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
           // $this->redirectToRoute('kontaktniosoba_index', array('id'=>$firma->getId()));
            return $this->redirectToRoute('kontaktniosoba_index', array('id'=>'2'));
            //return $this->redirectToRoute('kontaktniosoba_edit', array('id' => $kontaktniOsoba->getId()));


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
     * @Route("/smazat/{id}", name="kontaktniosoba_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, KontaktniOsoba $kontaktniOsoba,   FlashBagInterface $flashBag)
    {

       $idfirmy=$kontaktniOsoba->getFirma()->getId();
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($kontaktniOsoba);
            $em->flush();
            $flashBag->add('success', 'Kontaktní osoba byla úspěšně smazána.');
        }
        catch (ForeignKeyConstraintViolationException $e) {
            $flashBag->add('danger', 'kontaktní osoba nemůže být smazána.');
        }
        return $this->redirectToRoute('kontaktniosoba_index',array('id'=>$idfirmy));
    }
        //return $this->redirectToRoute('kontaktniosoba_index', array('id'=>'2'));

        //return $this->redirectToRoute('kontaktniosoba_edit', array('id' => $kontaktniOsoba->getId()));


        //return $this->redirectToRoute('kontaktniosoba_index');


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
