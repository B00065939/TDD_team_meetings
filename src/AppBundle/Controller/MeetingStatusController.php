<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MeetingStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Meetingstatus controller.
 *
 * @Route("meetingstatus")
 */
class MeetingStatusController extends Controller
{
    /**
     * Lists all meetingStatus entities.
     *
     * @Route("/", name="meetingstatus_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $meetingStatuses = $em->getRepository('AppBundle:MeetingStatus')->findAll();

        return $this->render('meetingstatus/index.html.twig', array(
            'meetingStatuses' => $meetingStatuses,
        ));
    }

    /**
     * Creates a new meetingStatus entity.
     *
     * @Route("/new", name="meetingstatus_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $meetingStatus = new Meetingstatus();
        $form = $this->createForm('AppBundle\Form\MeetingStatusType', $meetingStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($meetingStatus);
            $em->flush($meetingStatus);

            return $this->redirectToRoute('meetingstatus_show', array('id' => $meetingStatus->getId()));
        }

        return $this->render('meetingstatus/new.html.twig', array(
            'meetingStatus' => $meetingStatus,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a meetingStatus entity.
     *
     * @Route("/{id}", name="meetingstatus_show")
     * @Method("GET")
     */
    public function showAction(MeetingStatus $meetingStatus)
    {
        $deleteForm = $this->createDeleteForm($meetingStatus);

        return $this->render('meetingstatus/show.html.twig', array(
            'meetingStatus' => $meetingStatus,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing meetingStatus entity.
     *
     * @Route("/{id}/edit", name="meetingstatus_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MeetingStatus $meetingStatus)
    {
        $deleteForm = $this->createDeleteForm($meetingStatus);
        $editForm = $this->createForm('AppBundle\Form\MeetingStatusType', $meetingStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('meetingstatus_edit', array('id' => $meetingStatus->getId()));
        }

        return $this->render('meetingstatus/edit.html.twig', array(
            'meetingStatus' => $meetingStatus,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a meetingStatus entity.
     *
     * @Route("/{id}", name="meetingstatus_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MeetingStatus $meetingStatus)
    {
        $form = $this->createDeleteForm($meetingStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($meetingStatus);
            $em->flush($meetingStatus);
        }

        return $this->redirectToRoute('meetingstatus_index');
    }

    /**
     * Creates a form to delete a meetingStatus entity.
     *
     * @param MeetingStatus $meetingStatus The meetingStatus entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MeetingStatus $meetingStatus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('meetingstatus_delete', array('id' => $meetingStatus->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
