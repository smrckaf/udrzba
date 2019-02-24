<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lokace;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * Lokace controller.
 *
 * @Route("lokace")
 */
class LokaceController extends Controller
{
    /**
     * Lists all lokace entities.
     *
     * @Route("/", name="lokace_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lokaces = $em->getRepository('AppBundle:Lokace')->findAll();

        return $this->render('lokace/index.html.twig', array(
            'lokaces' => $lokaces,
        ));
    }

    /**
     * Creates a new lokace entity.
     *
     * @Route("/new", name="lokace_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $lokace = new Lokace();
        $form = $this->createForm('AppBundle\Form\LokaceType', $lokace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lokace);
            $em->flush();

            return $this->redirectToRoute('lokace_index');
        }

        return $this->render('lokace/new.html.twig', array(
            'lokace' => $lokace,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a lokace entity.
     *
     * @Route("/{id}", name="lokace_show")
     * @Method("GET")
     */
    public function showAction(Lokace $lokace)
    {
        $deleteForm = $this->createDeleteForm($lokace);

        return $this->render('lokace/show.html.twig', array(
            'lokace' => $lokace,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing lokace entity.
     *
     * @Route("/{id}/edit", name="lokace_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Lokace $lokace)
    {
        //$deleteForm = $this->createDeleteForm($lokace);
        $editForm = $this->createForm('AppBundle\Form\LokaceType', $lokace);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lokace_index');
        }

        return $this->render('lokace/edit.html.twig', array(
            'lokace' => $lokace,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a lokace entity.
     *
     * @Route("/smazatl/{lokace}", name="lokace_delete")
     */
    public function deleteAction(Lokace $lokace,    FlashBagInterface $flashBag)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($lokace);
            $em->flush();
            $flashBag->add('success', 'Lokace byla úspěšně smazána.');
        }
        catch (ForeignKeyConstraintViolationException $e) {
            $flashBag->add('danger', 'Lokace nemůže být smazána, protože existují stroje s danou lokací.');
        }
        return $this->redirectToRoute('lokace_index');
    }

    /**
     * Creates a form to delete a lokace entity.
     *
     * @param Lokace $lokace The lokace entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Lokace $lokace)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lokace_delete', array('id' => $lokace->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
