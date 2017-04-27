<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AgendaItem;
use AppBundle\Entity\AgendaStatus;
use AppBundle\Entity\ConductMeeting;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\MeetingAttendance;
use AppBundle\Entity\MinuteItem;
use AppBundle\Form\CommentMinuteItemForm;
use AppBundle\Form\PresenceForm;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ConductMeetingController
 * @package AppBundle\Controller
 */
class ConductMeetingController extends Controller
{
    /**
     * Router controller for next agenda of meeting
     *
     * @param Meeting $meeting
     * @param $agendaItemSequenceNo
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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
                'project' => $meeting->getProject(),
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
                'project' => $meeting->getProject(),
                'countAI' => $countOfAgreedAgendaItems,
                'currAgendaItem' => $currAgendaItem,
                'nextAISequenceNo' => $agendaItemSequenceNo + 1,
                'agreedAgenda' => $agreedAgenda,
            ));

        } elseif ($agendaItemSequenceNo == 3) {
            //dump($nextAI);die();
            $lastMeeting = $this->findPreviousCompletedMeeting($meeting);
            if ($lastMeeting == null) {
                $minuteItems = null;
            }else {
                $minuteItems = $lastMeeting->getMinuteItems();
            }

            return $this->render(':conductmeeting:third.html.twig', array(
                'pageHeader' => "Project: \"" . $meeting->getProject()->getTitle() . "\". Meeting: " . $meeting->getMDateTime()->format('d/m/y  H:m'),
                'subHeader' => "Agenda: " . $currAgendaItem->getTitle(),
                'meeting' => $meeting,
                'project' => $meeting->getProject(),
                'countAI' => $countOfAgreedAgendaItems,
                'currAgendaItem' => $currAgendaItem,
                'nextAISequenceNo' => $agendaItemSequenceNo + 1,
                'meetingStatus' => $meeting->getMeetingStatus()->getName(),
                'lastMeeting' => $lastMeeting,
                'minuteItems' => $minuteItems,
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
                $this->addFlash('error', 'Something is wrong');
                return $this->redirectToRoute('show_project', ['project' => $meeting->getProject()->getId()]);
            }

            // $minuteItems = $em->getRepository(MinuteAction::class)->findBy(['minuteItem' => $meeting]);


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

    /**
     * Find Previous Completed Meeting
     * @param Meeting $meeting
     * @return Meeting|null
     */
    private function findPreviousCompletedMeeting(Meeting $meeting)
    {
        $meetingList = $meeting->getProject()->getMeetings();
        foreach ($meetingList as $item) {
            if ($item->getMeetingStatus()->getName() != 'past') {
                $meetingList->removeElement($item);
            }
        }
        foreach ($meetingList as $item) {
            if ($item->getMDateTime() >= $meeting->getMDateTime()) {
                $meetingList->removeElement($item);
            }
        }
        if (count($meetingList) == 0) {
            return null;
        }
        if (count($meetingList) > 0) {
            $lastMeeting = $meetingList->first();
            foreach ($meetingList as $item) {
                if ($lastMeeting->getMDateTime() < $item->getMDateTime()) {
                    $lastMeeting = $item;
                }
            }
            return $lastMeeting;
        } else {
            return null;
        }
    }

    /**
     * ROLE_FINISH_MEETING
     * @Security("is_granted('ROLE_FINISH_MEETING')")
     * Route controller
     * @param Meeting $meeting
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
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

    /**
     * Route controller
     * @param Meeting $meeting
     * @param AgendaItem $agendaItem
     * @param MeetingAttendance $meetingAttendance
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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
            'meetingStatus' => $meeting->getMeetingStatus()->getName(),
            'nextAISequenceNo' => $agendaItem->getSequenceNo() + 1,
            'currAgendaItem' => $agendaItem,
            'usersAttendanceList' => $meeting->getMeetingAttendances(),
            'form' => $form->createView(),
            'meetingAttendance' => $meetingAttendance,
        ));
    }

    /**
     * Route controller
     * @Security("is_granted('ROLE_START_MEETING')")
     * @param Meeting $meeting
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function startAction(Meeting $meeting)
    {
        $em = $this->getDoctrine()->getManager();
        $draftAIStatus = $em->getRepository(AgendaStatus::class)->findOneBy(['name' => "draft"]);
        $secretary = $em->getRepository('AppBundle:ProjectHasUser')->findProjectUserWithRole($meeting->getProject(), "Project Secretary");
//        if ($this->getUser() != $secretary || $meeting->getProject()->isLock()) {
//            $this->addFlash('error', 'No access');
//            return $this->redirectToRoute('homepage');
//        }
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
        if ($meeting->getAgendaDeadline() > new \DateTime()) {

            $meeting->setAgendaDeadline(new \DateTime());
            $em->persist($meeting);
            $em->flush();
        }
        $lastAISequenceNo = $conductMeeting->getCurrentAgendaItem()->getSequenceNo();
        $currAI = $conductMeeting->getCurrentAgendaItem();
        $project = $meeting->getProject();
        $countAI = $this->calculateNoOfAI($meeting);

        return $this->render(':conductmeeting:start.html.twig', array(
            'pageHeader' => "Welcome to the \"" . $project->getTitle() . "\" project meeting",
            'subHeader' => "We will conduct a meeting scheduled on - " . $meeting->getMDateTime()->format('d/m/y  H:m'),
            'meeting' => $meeting,
            'currAI' => $currAI,
            'countAI' => $countAI,
            'project' => $project,
            'secretary' => $secretary,
            'lastAISequenceNo' => $lastAISequenceNo,
        ));
    }

    /**
     * Calculate No of "draft" agenda items for passed meeting
     * @param Meeting $meeting
     * @return int
     */
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

    /**
     * Create Agenda Minutes from collection of agreed agenda items
     * @param $agreedAgenda
     */
    private function createAgendaMinutes($agreedAgenda)
    {
        $em = $this->getDoctrine()->getManager();
        $status = $em->getRepository(AgendaStatus::class)->findOneBy(['name' => "draft minute"]);
        // sprawdz czy istnieja minuty dla tego spotkania
        $meeting = $agreedAgenda[0]->getMeeting();
        $minutes = $em->getRepository(MinuteItem::class)->findBy(['meeting' => $meeting]);
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
