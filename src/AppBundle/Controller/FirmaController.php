<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Firma;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Firma controller.
 *
 * @Route("firma")
 */
class FirmaController extends Controller
{
    /**
     * Lists all firma entities.
     *
     * @Route("/", name="firma_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $firmas = $em->getRepository('AppBundle:Firma')->findAll();

        return $this->render('firma/index.html.twig', array(
            'firmas' => $firmas,
        ));
    }

    /**
     * Creates a new firma entity.
     *
     * @Route("/new", name="firma_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $firma = new Firma();
        $form = $this->createForm('AppBundle\Form\FirmaType', $firma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($firma);
            $em->flush();

            return $this->redirectToRoute('firma_index');
        }

        return $this->render('firma/new.html.twig', array(
            'firma' => $firma,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a firma entity.
     *
     * @Route("/show/{id}", name="firma_show")
     * @Method("GET")
     */
    public function showAction(Firma $firma)
    {
          return $this->render('firma/show.html.twig', array(
            'firma' => $firma,
          ));
    }

    /**
     * Displays a form to edit an existing firma entity.
     *
     * @Route("/{id}/edit", name="firma_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Firma $firma)
    {

        $editForm = $this->createForm('AppBundle\Form\FirmaType', $firma);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('firma_index');
        }

        return $this->render('firma/edit.html.twig', array(
            'firma' => $firma,
            'edit_form' => $editForm->createView(),

        ));
    }

    /**
     * Deletes a firma entity.
     *
     * @Route("/smazatf/{firma}", name="firma_delete")
     */
    public function deleteAction(Firma $firma, FlashBagInterface $flashBag)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($firma);
            $em->flush();
            $flashBag->add('success', 'Firma byla úspěšně smazána.');
        }
        catch (ForeignKeyConstraintViolationException $e) {
            $flashBag->add('danger',  'Firma nemůže být smazána, protože existují pracovníci v této firmě.');
        }
        return $this->redirectToRoute('firma_index');
    }


    /**
     * Creates a form to delete a firma entity.
     *
     * @param Firma $firma The firma entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Firma $firma)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('firma_delete', array('id' => $firma->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
