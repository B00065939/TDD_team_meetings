<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\MeetingAttendance;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectHasUser;
use AppBundle\Entity\User;
use AppBundle\Form\AddUserToCurrentProjectForm;
use AppBundle\Form\AddUserToProjectForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectHasUserController extends Controller
{
    /**
     * @Security("is_granted('ROLE_REMOVE_USER_FROM_PROJECT')")
     * @param Project $project
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Project $project, User $user, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entryToRemove = $em->getRepository(ProjectHasUser::class)->findOneBy([
            'project' => $project,
            'user' => $user
        ]);
        $em->remove($entryToRemove);
        $em->flush();
        $this->removeUserToMeetingsAttendance($user, $project);
        $this->addFlash('success', "User removed from project");
        return $this->redirectToRoute('show_project', array('project' => $project->getId()));
    }

    /**
     * @Security("is_granted('ROLE_ADD_USER_TO_CURRENT_PROJECT')")
     * @param Project $project
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addToAction(Project $project, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($em->find('AppBundle:Project', $project->getId()) == null) {
            $this->addFlash('error', 'Something went wrong!');
            return $this->redirectToRoute('homepage');
        }
//        $secretary = $em->getRepository('AppBundle:ProjectHasUser')->findProjectUserWithRole($project, "Project Secretary");
        $leader = $em->getRepository('AppBundle:ProjectHasUser')->findProjectUserWithRole($project, "Project Leader");
        $supervisor = $em->getRepository('AppBundle:ProjectHasUser')->findProjectUserWithRole($project, "Project Supervisor");
        if ( ($supervisor != $this->getUser()) && ($leader != $this->getUser())) {
            $this->addFlash('error', 'To add user to the project you need to be project leader!');
            return $this->redirectToRoute('show_project', ['project' => $project->getId()]);
        }
        $form = $this->createForm(AddUserToCurrentProjectForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $userToAdd = $form->get('userToAdd')->getData();
            $roleToAdd = $form->get('projectRole')->getData();

            $em = $this->getDoctrine()->getManager();
            $jhr = $em->getRepository(ProjectHasUser::class)->findBy([
                'project' => $project,
                'user' => $userToAdd
            ]);
            if ($jhr == null) {
                /**
                 * @var ProjectHasUser $projectHasUser
                 */
                $projectHasUser = new ProjectHasUser();
                $projectHasUser->setProject($project);
                $projectHasUser->setUser($userToAdd);
                $projectHasUser->setProjectRole($roleToAdd);
                $this->addUserToMeetingsAttendance($userToAdd, $project);
                $em->persist($projectHasUser);
                $em->flush();
                $this->addFlash('success', "User added to the project");
            } else {
                $this->addFlash('error', "User already has a role in this project");
            }
            return $this->redirectToRoute('add_user_to_current_project', ['project' => $project->getId()]);
        }

        return $this->render('project/adduserstoproject.html.twig', array(
            "pageHeader" => "Project: \"" . $project->getTitle() . "\"",
            "subHeader" => "Add user to this project",
            'project' => $project,
            "form" => $form->createView(),
        ));
    }

    private function addUserToMeetingsAttendance(User $user, Project $project){
        $em = $this->getDoctrine()->getManager();
        $meetings = $project->getMeetings();
        foreach ($meetings as $meeting) {
            if ($meeting->getMeetingStatus()->getName() == 'future') {
                $attendance = new MeetingAttendance();
                $attendance->setMeeting($meeting);
                $attendance->setUser($user);
                $attendance->setAttendance("Yes");
                $attendance->setApologiesNote("");
                $em->persist($attendance);
                $em->flush();
            }
        }
    }


    private function removeUserToMeetingsAttendance(User $user, Project $project){
        $em = $this->getDoctrine()->getManager();


        $meetings = $project->getMeetings();
        foreach ($meetings as $meeting) {
            if ($meeting->getMeetingStatus()->getName() == 'future') {
                $attendance = $em->getRepository(MeetingAttendance::class)->findOneBy(['user' => $user, 'meeting' =>$meeting]);
                $em->remove($attendance);
                $em->flush();
            }
        }
    }
    /**
     * @Security("is_granted('ROLE_ADD_USER_TO_PROJECT')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        //Project $project, ProjectRole $projectRole, User $user,
        $form = $this->createForm(AddUserToProjectForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $projectToAdd = $form->get('project')->getData();
            $userToAdd = $form->get('userToAdd')->getData();
            $roleToAdd = $form->get('projectRole')->getData();

            $em = $this->getDoctrine()->getManager();
            //$jhr = $this->getDoctrine()->getRepository(ProjectHasUser::class)->findIfUserHasRoleInProject($projectToAdd, $roleToAdd, $userToAdd);
            $jhr = $em->getRepository(ProjectHasUser::class)->findBy([
                'project' => $projectToAdd,
                'user' => $userToAdd
            ]);
            if ($jhr == null) {
                /**
                 * @var ProjectHasUser $projectHasUser
                 */
                $projectHasUser = new ProjectHasUser();
                $projectHasUser->setProject($projectToAdd);
                $projectHasUser->setUser($userToAdd);
                $projectHasUser->setProjectRole($roleToAdd);


                $em->persist($projectHasUser);
                $em->flush();
                $this->addFlash('success', "User added to the project");
            } else {
                $this->addFlash('error', "User already has a role in this project");
            }
            return $this->redirectToRoute('add_user_to_project');
        }

        return $this->render('project/adduserstoproject.html.twig', array(
            "pageHeader" => "Project supervision",
            "subHeader" => "Add user to the project",

            "form" => $form->createView()
        ));
    }


}
