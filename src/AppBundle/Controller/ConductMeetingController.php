<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AgendaItem;
use AppBundle\Entity\AgendaStatus;
use AppBundle\Entity\ConductMeeting;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\MeetingAttendance;
use AppBundle\Entity\MinuteAction;
use AppBundle\Entity\MinuteItem;
use AppBundle\Form\CommentMinuteItemForm;
use AppBundle\Form\MinuteActionForm;
use AppBundle\Form\PresenceForm;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ConductMeetingController extends Controller
{
    public function nextAction(Meeting $meeting, $agendaItemSequenceNo, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $pastMeetingStatus = $em->getRepository('AppBundle:MeetingStatus')->findOneBy(['name' => 'past']);
        $draftAIStatus = $em->getRepository(AgendaStatus::class)->findOneBy(['name' => "draft"]);

        // prepare agenda items for meeting all items where status = draft
        // going to use it to find next sequence No and number fo aggenda items
        $agreedAgenda = $meeting->getAgendaItems();
        foreach ($agreedAgenda as $item) {
            if ($item->getStatus() != $draftAIStatus) {
                $agreedAgenda->removeElement($item);
            }
        }

        $countOfAgreedAgendaItems = count($agreedAgenda);

        $currAgendaItem = $em->getRepository(AgendaItem::class)->findOneBy([
            'sequenceNo' => $agendaItemSequenceNo,
            'meeting' => $meeting,
            'status' => $draftAIStatus,
        ]);

        if ($agendaItemSequenceNo == 1) {

            return $this->render(':conductmeeting:first.html.twig', array(
                'pageHeader' => "Project: \"" . $meeting->getProject()->getTitle() . "\". Meeting: " . $meeting->getMDateTime()->format('d/m/y  H:m'),
                'subHeader' => "Agenda: " . $currAgendaItem->getTitle(),
                'meeting' => $meeting,
                'countAI' => $countOfAgreedAgendaItems,
                // "project" => $project,
                'nextAISequenceNo' => $agendaItemSequenceNo + 1,
                'currAgendaItem' => $currAgendaItem,
                'usersAttendanceList' => $meeting->getMeetingAttendances(),
                'meetingStatus' => $meeting->getMeetingStatus()->getName(),
            ));

        } elseif ($agendaItemSequenceNo == 2) {
            /**
             * @var ArrayCollection $agreedAgenda
             */
            $agreedAgenda = $meeting->getAgendaItems();
            foreach ($agreedAgenda as $item) {
                if ($item->getStatus() != $draftAIStatus) {
                    $agreedAgenda->removeElement($item);
                }
            }
            // all draft agenda items become draft minutes
            $this->createAgendaMinutes($agreedAgenda);
            return $this->render(':conductmeeting:second.html.twig', array(
                'pageHeader' => "Project: \"" . $meeting->getProject()->getTitle() . "\". Meeting: " . $meeting->getMDateTime()->format('d/m/y  H:m'),
                'subHeader' => "Agenda: " . $currAgendaItem->getTitle(),
                'meeting' => $meeting,
                'countAI' => $countOfAgreedAgendaItems,
                'currAgendaItem' => $currAgendaItem,
                'nextAISequenceNo' => $agendaItemSequenceNo + 1,
                'agreedAgenda' => $agreedAgenda,
            ));

        } elseif ($agendaItemSequenceNo == 3) {
            //dump($nextAI);die();
            return $this->render(':conductmeeting:third.html.twig', array(
                'pageHeader' => "Project: \"" . $meeting->getProject()->getTitle() . "\". Meeting: " . $meeting->getMDateTime()->format('d/m/y  H:m'),
                'subHeader' => "Agenda: " . $currAgendaItem->getTitle(),
                'meeting' => $meeting,
                'countAI' => $countOfAgreedAgendaItems,
                'currAgendaItem' => $currAgendaItem,
                'nextAISequenceNo' => $agendaItemSequenceNo + 1,
            ));
        } else {
            /**
             * @var MinuteItem $minuteItem
             */
            $minuteItem = $em->getRepository(MinuteItem::class)->findOneBy([
                'meeting' => $meeting,
                'sequenceNo' => $agendaItemSequenceNo
            ]);

            if ($minuteItem == null) {
                $this->addFlash('error','Something is wrong');
                return $this->redirectToRoute('show_project',['project' => $meeting->getProject()->getId()]);
            }

            $minuteItems = $em->getRepository(MinuteAction::class)->findBy(['minuteItem' => $meeting]);


            $formCommentMinute = $this->createForm(CommentMinuteItemForm::class, $minuteItem);
            $formCommentMinute->handleRequest($request);
            if ($formCommentMinute->isSubmitted() && $formCommentMinute->isValid()) {
                $em->persist($minuteItem);
                $em->flush();
                $this->addFlash('success', "Comment Updated");

                return $this->redirectToRoute('next_agenda_on_meeting', ['meeting' => $minuteItem->getMeeting()->getId(), 'agendaItemSequenceNo' => $agendaItemSequenceNo]);
            }

            return $this->render(':conductmeeting:next.html.twig', array(
                'pageHeader' => "Project: \"" . $meeting->getProject()->getTitle() . "\". Meeting: " . $meeting->getMDateTime()->format('d/m/y  H:m'),
                'subHeader' => "Agenda: " . $minuteItem->getTitle(),
                'meeting' => $meeting,
                'project' => $meeting->getProject(),
                'meetingStatus' => $meeting->getMeetingStatus()->getName(),
                'countAI' => $countOfAgreedAgendaItems,
                'minuteItem' => $minuteItem,
                'nextAISequenceNo' => $agendaItemSequenceNo + 1,
                'form' => $formCommentMinute->createView(),
            ));
        }

    }

    public function finishMeetingAction(Meeting $meeting, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $test = $em->find('AppBundle:Meeting', $meeting->getId());
        if ($test == null) {
            $this->addFlash('error', 'Action not allowed');
            return $this->redirectToRoute('homepage');
        }
        $status = $em->getRepository('AppBundle:MeetingStatus')->findOneBy(['name' => 'past']);
        $meeting->setMeetingStatus($status);
        $em->persist($meeting);
        $em->flush();
        $this->addFlash('success', 'The meeting is complete, you can check its progress in the past meetings table ');
        return $this->redirectToRoute('show_project', ['project' => $meeting->getProject()->getId()]);

    }

    public function presenceCheckAction(Meeting $meeting, AgendaItem $agendaItem, MeetingAttendance $meetingAttendance, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $agreedAgenda = $meeting->getAgendaItems();
        $draftAIStatus = $em->getRepository(AgendaStatus::class)->findOneBy(['name' => "draft"]);
        foreach ($agreedAgenda as $item) {
            if ($item->getStatus() != $draftAIStatus) {
                $agreedAgenda->removeElement($item);
            }
        }

        $countOfAgreedAgendaItems = count($agreedAgenda);


        $countAI = $this->calculateNoOfAI($meeting);
        $project = $meeting->getProject();
        $form = $this->createForm(PresenceForm::class, $meetingAttendance);

        $form->handleRequest($request);
        $usersAttendanceList = $meeting->getMeetingAttendances();
        $nextAI = $em->getRepository(AgendaItem::class)->findOneBy([
            'sequenceNo' => $agendaItem->getSequenceNo() + 1,
            'meeting' => $meeting,
            'status' => $agendaItem->getStatus(),
        ]);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('next_agenda_on_meeting', ['meeting' => $meeting->getId(), 'agendaItemSequenceNo' => $agendaItem->getSequenceNo()]);
        }

        return $this->render(':conductmeeting:first.html.twig', array(
            'pageHeader' => "Project: \"" . $meeting->getProject()->getTitle() . "\". Meeting: " . $meeting->getMDateTime()->format('d/m/y  H:m'),
            'subHeader' => "Agenda: " . $agendaItem->getTitle(),
            'meeting' => $meeting,
            'countAI' => $countOfAgreedAgendaItems,
            // "project" => $project,
            'nextAISequenceNo' => $agendaItem->getSequenceNo() + 1,
            'currAgendaItem' => $agendaItem,
            'usersAttendanceList' => $meeting->getMeetingAttendances(),
            'form' => $form->createView(),
            'meetingAttendance' => $meetingAttendance,
        ));
    }


    public function startAction(Meeting $meeting)
    {

        $em = $this->getDoctrine()->getManager();
        $draftAIStatus = $em->getRepository(AgendaStatus::class)->findOneBy(['name' => "draft"]);

        $conductMeeting = $em->getRepository(ConductMeeting::class)->findOneBy(['meeting' => $meeting]);
        if ($conductMeeting == null) {
            $conductMeeting = new ConductMeeting();
            $conductMeeting->setMeeting($meeting);
            $conductMeeting->setCurrentAgendaItem($em->getRepository(AgendaItem::class)->findOneBy([
                'meeting' => $meeting,
                'sequenceNo' => 1,
            ]));
            $em->persist($conductMeeting);
            $em->flush();
        }

        /**
         * @var ArrayCollection $agreedAgenda
         */
        $agreedAgenda = $meeting->getAgendaItems();
        foreach ($agreedAgenda as $item) {
            if ($item->getStatus() != $draftAIStatus) {
                $agreedAgenda->removeElement($item);
            }
        }
        // all draft agenda items become draft minutes
        $this->createAgendaMinutes($agreedAgenda);
        if ($meeting->getAgendaDeadline() > new \DateTime()){

            $meeting->setAgendaDeadline(new \DateTime());
            $em->persist($meeting);
            $em->flush();
        }
        $lastAISequenceNo = $conductMeeting->getCurrentAgendaItem()->getSequenceNo();
        $currAI = $conductMeeting->getCurrentAgendaItem();
        $project = $meeting->getProject();
        $countAI = $this->calculateNoOfAI($meeting);

        return $this->render(':conductmeeting:start.html.twig', array(
            "pageHeader" => "Welcome to the \"" . $project->getTitle() . "\" project meeting",
            "subHeader" => "We will conduct a meeting scheduled for - " . $meeting->getMDateTime()->format('d/m/y  H:m'),
            "meeting" => $meeting,
            "currAI" => $currAI,
            'countAI' => $countAI,
            "project" => $project,
            "lastAISequenceNo" => $lastAISequenceNo,
        ));
    }

    private function calculateNoOfAI(Meeting $meeting)
    {
        $agendaItems = $meeting->getAgendaItems();
        $countAI = 0;
        foreach ($agendaItems as $item) {
            if ($item->getStatus() == "draft") {
                $countAI++;
            }
        }
        return $countAI;
    }

    private function createAgendaMinutes($agreedAgenda)
    {
        $em = $this->getDoctrine()->getManager();
        $status = $em->getRepository(AgendaStatus::class)->findOneBy(['name' => "draft minute"]);

//        sprawdz czy istnieja minuty dla tego spotkania
        $meeting = $agreedAgenda[0]->getMeeting();
        //[0]->getMeeting;
        $minutes = $em->getRepository(MinuteItem::class)->findBy(['meeting' => $meeting]);
        // dump($meeting);die();
        if ($minutes == null) {
            foreach ($agreedAgenda as $item) {
                $mi = new MinuteItem();
                $mi->setCreationDate(new \DateTime());
                $mi->setMeeting($item->getMeeting());
                $mi->setSequenceNo($item->getSequenceNo());
                $mi->setStatus($status);
                $mi->setDescription($item->getDescription());
                $mi->setTitle($item->getTitle());
                $mi->setProposer($item->getProposer());

                $em->persist($mi);
                $em->flush();
            }

        }
    }
}
