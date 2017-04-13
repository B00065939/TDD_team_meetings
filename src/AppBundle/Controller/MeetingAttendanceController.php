<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MeetingAttendance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Meetingattendance controller.
 *
 * @Route("meetingattendance")
 */
class MeetingAttendanceController extends Controller
{
    /**
     * Lists all meetingAttendance entities.
     *
     * @Route("/", name="meetingattendance_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $meetingAttendances = $em->getRepository('AppBundle:MeetingAttendance')->findAll();

        return $this->render('meetingattendance/index.html.twig', array(
            'meetingAttendances' => $meetingAttendances,
        ));
    }

    /**
     * Creates a new meetingAttendance entity.
     *
     * @Route("/new", name="meetingattendance_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $meetingAttendance = new Meetingattendance();
        $form = $this->createForm('AppBundle\Form\MeetingAttendanceType', $meetingAttendance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($meetingAttendance);
            $em->flush($meetingAttendance);

            return $this->redirectToRoute('meetingattendance_show', array('id' => $meetingAttendance->getId()));
        }

        return $this->render('meetingattendance/new.html.twig', array(
            'meetingAttendance' => $meetingAttendance,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a meetingAttendance entity.
     *
     * @Route("/{id}", name="meetingattendance_show")
     * @Method("GET")
     */
    public function showAction(MeetingAttendance $meetingAttendance)
    {
        $deleteForm = $this->createDeleteForm($meetingAttendance);

        return $this->render('meetingattendance/show.html.twig', array(
            'meetingAttendance' => $meetingAttendance,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing meetingAttendance entity.
     *
     * @Route("/{id}/edit", name="meetingattendance_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MeetingAttendance $meetingAttendance)
    {
        $deleteForm = $this->createDeleteForm($meetingAttendance);
        $editForm = $this->createForm('AppBundle\Form\MeetingAttendanceType', $meetingAttendance);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('meetingattendance_edit', array('id' => $meetingAttendance->getId()));
        }

        return $this->render('meetingattendance/edit.html.twig', array(
            'meetingAttendance' => $meetingAttendance,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a meetingAttendance entity.
     *
     * @Route("/{id}", name="meetingattendance_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MeetingAttendance $meetingAttendance)
    {
        $form = $this->createDeleteForm($meetingAttendance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($meetingAttendance);
            $em->flush($meetingAttendance);
        }

        return $this->redirectToRoute('meetingattendance_index');
    }

    /**
     * Creates a form to delete a meetingAttendance entity.
     *
     * @param MeetingAttendance $meetingAttendance The meetingAttendance entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MeetingAttendance $meetingAttendance)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('meetingattendance_delete', array('id' => $meetingAttendance->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
