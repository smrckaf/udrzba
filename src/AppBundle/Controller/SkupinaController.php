<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Skupina;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Skupina controller.
 *
 * @Route("skupina")
 */
class SkupinaController extends Controller
{
    /**
     * Lists all skupina entities.
     *
     * @Route("/", name="skupina_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $skupinas = $em->getRepository('AppBundle:Skupina')->findAll();

        return $this->render('skupina/index.html.twig', array(
            'skupinas' => $skupinas,
        ));
    }

    /**
     * Creates a new skupina entity.
     *
     * @Route("/new", name="skupina_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $skupina = new Skupina();
        $form = $this->createForm('AppBundle\Form\SkupinaType', $skupina);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($skupina);
            $em->flush();
            return $this->redirectToRoute('skupina_index');
            //return $this->redirectToRoute('skupina_show', array('id' => $skupina->getId()));
        }

        return $this->render('skupina/new.html.twig', array(
            'skupina' => $skupina,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a skupina entity.
     *
     * @Route("/{id}", name="skupina_show")
     * @Method("GET")
     */
    public function showAction(Skupina $skupina)
    {
        $deleteForm = $this->createDeleteForm($skupina);

        return $this->render('skupina/show.html.twig', array(
            'skupina' => $skupina,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing skupina entity.
     *
     * @Route("/{id}/edit", name="skupina_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Skupina $skupina)
    {
        $deleteForm = $this->createDeleteForm($skupina);
        $editForm = $this->createForm('AppBundle\Form\SkupinaType', $skupina);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('skupina_index');
            //return $this->redirectToRoute('skupina_edit', array('id' => $skupina->getId()));
        }

        return $this->render('skupina/edit.html.twig', array(
            'skupina' => $skupina,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a skupina entity.
     *
     * @Route("/{id}", name="skupina_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Skupina $skupina)
    {
        $form = $this->createDeleteForm($skupina);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($skupina);
            $em->flush();
        }

        return $this->redirectToRoute('skupina_index');
    }
    /**
     * @Route("/smazat2/{skupina}", name="skupina2-smazat")
     */
    public function smazat2(Skupina $skupina, FlashBagInterface $flashBag)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($skupina);
            $em->flush();
            $flashBag->add('success', 'Skupina byla úspěšně smazána.');
        }
        catch (ForeignKeyConstraintViolationException $e) {
            $flashBag->add('danger',  'Skupina nemůže být smazána, protože existují stroje s danou skupinou.');
        }
        return $this->redirectToRoute('skupina_index');
    }



    /**
     * Creates a form to delete a skupina entity.
     *
     * @param Skupina $skupina The skupina entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Skupina $skupina)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('skupina_delete', array('id' => $skupina->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
