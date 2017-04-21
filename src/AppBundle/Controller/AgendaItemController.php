<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AgendaItem;
use AppBundle\Entity\AgendaStatus;
use AppBundle\Entity\Meeting;
use AppBundle\Form\AgendaItemUpdateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AgendaItemController
 * @package AppBundle\Controller
 */
class AgendaItemController extends Controller
{
    /**
     * @Security("is_granted('ROLE_MAKE_AGENDA_ITEM_CURRENT')")
     * Make agenda current
     * @param Request $request
     * @param AgendaItem $agendaItem
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function makeCurrentAction(Request $request, AgendaItem $agendaItem)
    {
        $em = $this->getDoctrine()->getManager();
        // get agenda version to replace
        $currAI = $agendaItem->getUpdateFor();

        $maxSequenceNo = 0;
        $minSequenceNo = 4;
        $currSequenceNo = $currAI->getSequenceNo();
        $newSequenceNo = $agendaItem->getSequenceNo();

        $agendaItems = $agendaItem->getMeeting()->getAgendaItems();

        //znajdujemy $maxSequenceNo
        foreach ($agendaItems as $item) {
            if ($item->getStatus() == "draft" && $item->getSequenceNo() >= $maxSequenceNo) {
                $maxSequenceNo = $item->getSequenceNo();
            }
        }

        // jescli z formularza sequence number wiekszy niz max asign max
        if ($newSequenceNo < $currSequenceNo) {
            if ($newSequenceNo < $minSequenceNo) {
                $newSequenceNo = $minSequenceNo;
            }
            foreach ($agendaItems as $item) {
                if ($item->getSequenceNo() >= $newSequenceNo && $item->getSequenceNo() < $currSequenceNo && $item->getStatus() != null && $item->getStatus()->getName() == "draft") {
                    $item->setSequenceNo($item->getSequenceNo() + 1);
                }
            }
        } elseif ($newSequenceNo > $currSequenceNo) {
            if ($newSequenceNo >= $maxSequenceNo) {
                $agendaItem->setSequenceNo($maxSequenceNo);
            }
            foreach ($agendaItems as $item) {
                if ($item->getSequenceNo() > $currSequenceNo && $item->getSequenceNo() <= $maxSequenceNo && $item !== $currAI && $item->getStatus() != null && $item->getStatus()->getName() == "draft") {
                    $item->setSequenceNo($item->getSequenceNo() + 1);
                }
            }
            foreach ($agendaItems as $item) {
                if ($item->getSequenceNo() > $currSequenceNo && $item->getSequenceNo() <= $newSequenceNo && $item->getStatus()->getName() == "draft") {
                    $item->setSequenceNo($item->getSequenceNo() - 1);
                }
            }
        }

        $nextAgendaItems = $currAI->getNextVersions();
        foreach ($nextAgendaItems as $item) {
            $item->setUpdateFor($agendaItem);

        }
        $prevAgendaItems = $currAI->getPrevVersions();
        foreach ($prevAgendaItems as $item) {
            $item->setReplacedBy($agendaItem);
        }

        // current AI is replaced by new one and get status null
        $currAI->setReplacedBy($agendaItem);
        $currAI->setStatus(null);

        // New agenda get status "draft" and become curr agenda
        $agendaItem->setStatus($em->getRepository(AgendaStatus::class)->findOneBy(['name' => 'draft']));
        $agendaItem->setUpdateFor(null);

        $em->flush();
        return $this->redirectToRoute('show_agenda_item', array('agendaItem' => $agendaItem->getId()));

    }

    /**
     * @Security("is_granted('ROLE_SHOW_AGENDA_ITEM')")
     * Finds and displays a agendaItem entity.
     * @param Request $request
     * @param AgendaItem $agendaItem
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, AgendaItem $agendaItem)
    {
        $em = $this->getDoctrine()->getManager();

        $prevAgendaItems = $agendaItem->getPrevVersions();
        $nextAgendaItems = $agendaItem->getNextVersions();

        $newAgendaItem = new AgendaItem();

        $newAgendaItem->setTitle($agendaItem->getTitle());
        $newAgendaItem->setDescription($agendaItem->getDescription());
        $newAgendaItem->setSequenceNo($agendaItem->getSequenceNo());
        $newAgendaItem->setMeeting($agendaItem->getMeeting());

        $form = $this->createForm(AgendaItemUpdateType::class, $newAgendaItem);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $newAgendaItem->setProposer($this->getUser());
            $newAgendaItem->setCreationDate(new \DateTime());
            $newAgendaItem->setUpdateFor($agendaItem);

            $em->persist($newAgendaItem);

            $em->flush();

            return $this->redirectToRoute('show_agenda_item', array('agendaItem' => $agendaItem->getId()));

        }
        return $this->render('agendaitem/agendaitem.html.twig', array(
            'pageHeader' => "Project: \"" . $agendaItem->getMeeting()->getProject()->getTitle(). "\". Meeting at : " . $agendaItem->getMeeting()->getMDateTime()->format('Y-m-d H:i:s') ,
            'subHeader' => "Propose new agenda item version",
            'agendaItem' => $agendaItem,
            'prevAgendaItems' => $prevAgendaItems,
            'nextAgendaItems' => $nextAgendaItems,
            'form' => $form->createView(),
            'meeting' => $agendaItem->getMeeting(),
            'project' => $agendaItem->getMeeting()->getProject(),
        ));

    }

    /**
     * @Security("is_granted('ROLE_NEW_AGENDA_ITEM')")
     * @param Meeting $meeting
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public
    function newAction(Meeting $meeting, Request $request)
    {
        $agendaItem = new AgendaItem();
        $form = $this->createForm('AppBundle\Form\AgendaItemType', $agendaItem);
        $form->handleRequest($request);

        $lastSequenceNo = 3;
        $agendaItems = $meeting->getAgendaItems();
        foreach ($agendaItems as $item) {
            if ($item->getStatus() == "draft" && $item->getSequenceNo() > $lastSequenceNo) {
                $lastSequenceNo = $item->getSequenceNo();
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $agendaItem->setMeeting($meeting);
            $agendaItem->setSequenceNo($lastSequenceNo + 1);
            $agendaItem->setCreationDate(new \DateTime());
            $agendaItem->setStatus($em->getRepository(AgendaStatus::class)->findOneBy(['name' => 'draft']));
            $agendaItem->setProposer($this->getUser());

            $em->persist($agendaItem);
            $em->flush();

            return $this->redirectToRoute('show_meeting', array('meeting' => $agendaItem->getMeeting()->getId()));
        }

        return $this->render(':agendaitem:newagendaitem.html.twig', array(
            'pageHeader' => "Project: \"" . $meeting->getProject()->getTitle() . "\". Meeting at : " . $meeting->getMDateTime()->format('Y-m-d H:i:s') ,
            'subHeader' => "Create new agenda item",
            'agendaItem' => $agendaItem,
            'form' => $form->createView(),
            'project' => $meeting->getProject(),
            'meeting' => $meeting,
        ));
    }
}
