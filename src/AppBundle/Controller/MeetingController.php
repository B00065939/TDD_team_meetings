<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AgendaItem;
use AppBundle\Entity\AgendaStatus;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\MeetingAttendance;
use AppBundle\Entity\MeetingStatus;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Form\ConfirmAttendance;
use AppBundle\Form\NewMeetingForm;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MeetingController extends Controller
{


    /**
     * @Security("is_granted('ROLE_SHOW_MEETING')")
     * @param Meeting $meeting
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Meeting $meeting, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        if($em->find('AppBundle:Meeting', $meeting->getId()) == null) {
            $this->addFlash('error', 'Something went wrong!');
            return $this->redirectToRoute('homepage');
        }
        $project = $meeting->getProject();
        $secretary = $em->getRepository('AppBundle:ProjectHasUser')->findProjectUserWithRole($project, "Project Secretary");
        $leader = $em->getRepository('AppBundle:ProjectHasUser')->findProjectUserWithRole($project, "Project Leader");
        $supervisor = $em->getRepository('AppBundle:ProjectHasUser')->findProjectUserWithRole($project, "Project Supervisor");



        $afterAgendaDeadline = $meeting->getAgendaDeadline() < new \DateTime();
        // all users for this meeting same as all user for this project
        $usersAttendanceList = $meeting->getMeetingAttendances();
        $currUserAttendance = $em->getRepository('AppBundle:MeetingAttendance')->findOneBy(['meeting' => $meeting, 'user' => $this->getUser()]);
        $currNote = $currUserAttendance->getApologiesNote();
        //$agendaItems = $em->getRepository('AppBundle:AgendaItem')->findBy(['meeting'-]),
        $agendaItems = $meeting->getAgendaItems();
        foreach ($agendaItems as $agendaItem) {
            if ($agendaItem->getStatus() != "draft") {
                $agendaItems->removeElement($agendaItem);
            }
        }

        //$test = $meeting->getProject()->getMeetings();
        //dump(count($agendaItems));die();
        $form = $this->createForm(ConfirmAttendance::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $attendance = $form->get('attendance')->getData();
            $note = $form->get('apologiesNote')->getData();

            $currUserAttendance->setApologiesNote($note);
            $currUserAttendance->setAttendance($attendance);
            $em->persist($currUserAttendance);
            $em->flush();
        }

        return $this->render('meeting/meeting.html.twig', array(
            "pageHeader" => "Project:  \"" . $meeting->getProject()->getTitle() . "\" ",
            "subHeader" => "Details of the meeting at : " . $meeting->getMDateTime()->format('Y-m-d H:i:s'),
            "usersAttendanceList" => $usersAttendanceList,
            "currUserAttendance" => $currUserAttendance,
            "currNote" => $currNote,
            'form' => $form->createView(),
            'agendaItems' => $agendaItems,
            'project' => $meeting->getProject(),
            'meeting' => $meeting,
            'leader' => $leader,
            'afterAgendaDeadline' => $afterAgendaDeadline,
        ));
    }

    /**
     * @Security("is_granted('ROLE_NEW_MEETING')")
     * @param Request $request
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $secretary = $em->getRepository('AppBundle:ProjectHasUser')->findProjectUserWithRole($project, "Project Secretary");
        $leader = $em->getRepository('AppBundle:ProjectHasUser')->findProjectUserWithRole($project, "Project Leader");
//        $supervisor = $em->getRepository('AppBundle:ProjectHasUser')->findProjectUserWithRole($project, "Project Supervisor");

        $form = $this->createForm(NewMeetingForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // najpierw dodaj meeting do bazy danych
            /**
             * @var Meeting $newMeeting
             */
            $newMeeting = new Meeting();

            /**
             * @var User $meetingChair
             */
            $meetingChair = $form->get('chair')->getData();

            /**
             * @var User $meetingSecretary
             */
            $meetingSecretary = $form->get('secretary')->getData();
            /**
             * @var MeetingStatus $meetingStatus
             */
            $meetingStatus = $em->getRepository(MeetingStatus::class)->findOneBy(['name' => "future"]);
            $location = $form->get('location')->getData();
            $mDateTime = $form->get('mDateTime')->getData();
            $aDateTime = $form->get('agendaDeadline')->getData();
            $duration = $form->get('duration')->getData();

            if ($meetingChair != null && $meetingChair === $meetingSecretary) {
                $errorMsg = "The Meeting chair and secretary need to be different person";

                $this->addFlash('error', "$errorMsg");
                return $this->redirectToRoute('new_meeting', array('project' => $project->getId()));

            } else {
                // Validation of chair and secretary
                if ($meetingChair == null) {
                    $meetingChair = $leader;
                }
                if ($meetingSecretary == null) {
                    $meetingSecretary = $secretary;
                }
            }

            if ($mDateTime < $aDateTime) {
                $errorMsg = "The meeting time must be at least one hour later than the agenda deadline ";
                $this->addFlash('error', "$errorMsg");
                return $this->redirectToRoute('new_meeting', array('project' => $project->getId()));
            }

            $currDate = new DateTime();
            date_add($currDate, date_interval_create_from_date_string('23 hour'));

            if ($mDateTime < $currDate) {
                $errorMsg = "The meeting time must be at least 24 hours later than current date ";
                $this->addFlash('error', "$errorMsg");
                return $this->redirectToRoute('new_meeting', array('project' => $project->getId()));
            }

            $newMeeting->setProject($project);
            $newMeeting->setChair($meetingChair);
            $newMeeting->setSecretary($meetingSecretary);
            $newMeeting->setAgendaDeadline($aDateTime);
            $newMeeting->setMDateTime($mDateTime);
            $newMeeting->setDuration($duration);
            $newMeeting->setLocation($location);
            $newMeeting->setMeetingStatus($meetingStatus);

            $em->persist($newMeeting);
            $em->flush();
            // Now we need to create +++++++ Meeting Attendance ++++++++
            $this->createMeetingAttendanceEntries($newMeeting);

            // Now we need to create ++++++++++ 3 Default Agenda Items +++
            $this->createThreeMandatoryAgendaItems($newMeeting);

            $this->addFlash('success', "Meeting was crated");
            return $this->redirectToRoute('show_project', array('project' => $project->getId()));
        }

        return $this->render('meeting/newmeeting.html.twig', array(
            "pageHeader" => "Project: \"" . $project->getTitle() . "\"",
            "subHeader" => "Create new meeting",
            "form" => $form->createView(),
            'project' => $project,
        ));
    }

    /**
     * @param Meeting $meeting
     */
    private function createThreeMandatoryAgendaItems(Meeting $meeting)
    {
        //dump($meeting->getId());die();
        /*
          * (1)	Note apologies received from team members unable to be present
          * (2)	agree agenda
          * (3)	Accept minutes of previous meeting (unless meeting is first for this project!)
         */

        $em = $this->getDoctrine()->getManager();
        $meeting = $em->find(Meeting::class, $meeting->getId());
        $draftStatus = $em->getRepository(AgendaStatus::class)->findOneBy(['name' => 'draft']);

        $firstAgendaItem = New AgendaItem();
        $firstAgendaItem->setMeeting($meeting);
        $firstAgendaItem->setProposer($meeting->getChair());
        $firstAgendaItem->setTitle("Apologies notes");
        $firstAgendaItem->setDescription("Note apologies received from team members unable to be present ");
        $firstAgendaItem->setSequenceNo(1);
        $firstAgendaItem->setStatus($draftStatus);
        $em->persist($firstAgendaItem);

        $secondAgendaItem = New AgendaItem();
        $secondAgendaItem->setMeeting($meeting);
        $secondAgendaItem->setProposer($meeting->getChair());
        $secondAgendaItem->setTitle("Agree agenda");
        $secondAgendaItem->setDescription("Meeting Secretary is presenting agree agenda ");
        $secondAgendaItem->setSequenceNo(2);
        $secondAgendaItem->setStatus($draftStatus);
        $em->persist($secondAgendaItem);

        $thirdAgendaItem = New AgendaItem();
        $thirdAgendaItem->setMeeting($meeting);
        $thirdAgendaItem->setProposer($meeting->getChair());
        $thirdAgendaItem->setTitle("Accept minutes");
        $thirdAgendaItem->setDescription("Accept minutes of previous meeting (unless meeting is first for this project!) ");
        $thirdAgendaItem->setSequenceNo(3);
        $thirdAgendaItem->setStatus($draftStatus);
        $em->persist($thirdAgendaItem);
        $em->flush();

    }

    /**
     * @param Meeting $meeting
     */
    private function createMeetingAttendanceEntries(Meeting $meeting)
    {
        $project = $meeting->getProject();
        $em = $this->getDoctrine()->getManager();
        // all users belonging to project
        $users = $em->getRepository('AppBundle:ProjectHasUser')->findAllUsersForProject($project);

        foreach ($users as $user) {
            /**
             * @var MeetingAttendance $attendance
             */
            $attendance = new MeetingAttendance();
            $attendance->setMeeting($meeting);
            $attendance->setUser($user);
            $attendance->setAttendance("Maybe");
            $attendance->setApologiesNote("");
            $em->persist($attendance);
            $em->flush();
        }
    }
}
