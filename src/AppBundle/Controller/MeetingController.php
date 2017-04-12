<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
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

        }

        return $this->render('meeting/newmeeting.html.twig', array(
            "pageHeader" => "Project supervising",
            "subHeader" => "Create new Project",
            "form" => $form->createView()

        ));
    }


}
