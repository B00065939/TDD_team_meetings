<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MeetingController extends Controller
{
    public function newAction()
    {

        return $this->render('meeting/newmeeting.html.twig', array(
            "pageHeader" => "Project supervising",
            "subHeader" => "Create new Project",
            "form" => $form->createView()

        ));
    }

    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }
}
