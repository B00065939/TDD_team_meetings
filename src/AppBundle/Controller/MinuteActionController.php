<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ActionStatus;
use AppBundle\Entity\AgendaItem;
use AppBundle\Entity\MinuteAction;
use AppBundle\Entity\MinuteItem;
use AppBundle\Form\MinuteActionForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MinuteActionController extends Controller
{

    /**
     * @param MinuteItem $minuteItem
     * @param MinuteAction $minuteAction
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(MinuteItem $minuteItem, MinuteAction $minuteAction)
    {
        //$minuteItem->get
        $em = $this->getDoctrine()->getManager();
        $em->remove($minuteAction);
        $em->flush();
        $this->addFlash('success', "Action was removed");
        return $this->redirectToRoute('next_agenda_on_meeting', ['meeting' => $minuteItem->getMeeting()->getId(), 'agendaItemSequenceNo' => $minuteItem->getSequenceNo()]);
    }

    /**
     * New Minute Action
     * @param MinuteItem $minuteItem
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(MinuteItem $minuteItem, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $minuteAction = new MinuteAction();
        $form = $this->createForm(MinuteActionForm::class, $minuteAction);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $minuteAction->setMinuteItem($minuteItem);
            $minuteAction->setStatus($em->getRepository(ActionStatus::class)->findOneBy(['name' => "in progress"]));
            $em->persist($minuteAction);
            $em->flush();

            $this->addFlash('success', "New action added");
            return $this->redirectToRoute('next_agenda_on_meeting', ['meeting' => $minuteItem->getMeeting()->getId(), 'agendaItemSequenceNo' => $minuteItem->getSequenceNo()]);
        }
        return $this->render('minuteaction/new.html.twig', array(
            'pageHeader' => "Project: \"" . $minuteItem->getMeeting()->getProject()->getTitle() . "\". Meeting: " . $minuteItem->getMeeting()->getMDateTime()->format('d/m/y  H:m'),
            'subHeader' => "Create new action for  \"" . $minuteItem->getTitle() . "\"",
            'minuteItem' => $minuteItem,
            'form' => $form->createView(),
        ));
    }
}
