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

    public function deleteAction(MinuteItem $minuteItem, MinuteAction $minuteAction)
    {
        //$minuteItem->get
        $em = $this->getDoctrine()->getManager();
        $em->remove($minuteAction);
        $em->flush();
        $this->addFlash('success', "Action was removed");
        return $this->redirectToRoute('next_agenda_on_meeting', ['meeting' => $minuteItem->getMeeting()->getId(), 'agendaItemSequenceNo' => $minuteItem->getSequenceNo()]);
    }

    public function newAction(MinuteItem $minuteItem, Request $request)
    {
        $minuteAction = new MinuteAction();

        $form = $this->createForm(MinuteActionForm::class, $minuteAction);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $em = $this->getDoctrine()->getManager();
            $minuteAction->setMinuteItem($minuteItem);
            $minuteAction->setStatus($em->getRepository(ActionStatus::class)->findOneBy(['name' => "in progress"]));

            $em->persist($minuteAction);
            $em->flush();

            $this->addFlash('success', "New action added");
            return $this->redirectToRoute('next_agenda_on_meeting', ['meeting' => $minuteItem->getMeeting()->getId(), 'agendaItemSequenceNo' => $minuteItem->getSequenceNo()]);
        }
        return $this->render('minuteaction/new.html.twig', array(
            'pageHeader' => "Project: \"" . $minuteItem->getMeeting()->getProject()->getTitle() . "\". Meeting: " . $minuteItem->getMeeting()->getMDateTime()->format('d/m/y  H:m'),
            'subHeader' => "Agenda: " . $minuteItem->getTitle(),
            'minuteItem' => $minuteItem,
            'form' => $form->createView(),
        ));
    }
}
