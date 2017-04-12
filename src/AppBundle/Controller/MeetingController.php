<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\MeetingStatus;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Form\NewMeetingForm;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MeetingController extends Controller
{
    public function newAction(Request $request, Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $secretary = $em->getRepository('AppBundle:ProjectHasUser')->findProjectUserWithRole($project, "Project Secretary");
        $leader = $em->getRepository('AppBundle:ProjectHasUser')->findProjectUserWithRole($project, "Project Leader");
        $supervisor = $em->getRepository('AppBundle:ProjectHasUser')->findProjectUserWithRole($project, "Project Supervisor");

        $dataTime = new DateTime();
        //$dataTime->format('Y-m-d H:i:s');
        //$dataTime->
        //dump($dataTime->format('Y-m-d H:i:s'));die();
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
            $meetingStatus = $em->getRepository(MeetingStatus::class)->findOneBy(['name' => "Future"]);
            $location = $form->get('secretary')->getData();
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
            dump($currDate);
            dump($mDateTime);

            if ($mDateTime < $currDate){
                $errorMsg = "The meeting time must be at least 24 hours later than current date ";
                $this->addFlash('error', "$errorMsg");
                return $this->redirectToRoute('new_meeting', array('project' => $project->getId()));
            }


            $newMeeting->setChair($meetingChair);
            $newMeeting->setSecretary($meetingSecretary);
            $newMeeting->setAgendaDeadline($aDateTime);
            $newMeeting->setMDateTime($mDateTime);
            $newMeeting->setDuration($duration);
            $newMeeting->setLocation($location);
            $newMeeting->setMeetingStatus($meetingStatus);


//            /**
//             * @var Project $project
//             */
//            $project = new Project();
//            $project->setTitle($form->get('title')->getData());
//            $project->setLock($form->get('lock')->getData());
//            $em->persist($project);

            //
            // stworz liste obecnosci ????
        }

        return $this->render('meeting/newmeeting.html.twig', array(
            "pageHeader" => "Project supervising",
            "subHeader" => "Create new meeting for project: ".$project->getTitle(),
            "form" => $form->createView()
        ));
    }


}
