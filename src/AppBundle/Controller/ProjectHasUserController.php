<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectHasUser;
use AppBundle\Entity\User;
use AppBundle\Form\AddUserToProjectForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectHasUserController extends Controller
{
    public function addAction(Request $request)
    {
        //Project $project, ProjectRole $projectRole, User $user,
        $form = $this->createForm(AddUserToProjectForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $projectToAdd = $form->get('project')->getData();
            $userToAdd = $form->get('userToAdd')->getData();
            $roleToAdd = $form->get('projectRole')->getData();
            $jhr = $this->getDoctrine()->getRepository(ProjectHasUser::class)->findIfUserHasRoleInProject($projectToAdd, $roleToAdd, $userToAdd);
            if ($jhr = null) {
                /**
                 * @var ProjectHasUser $projectHasUser
                 */
                $projectHasUser =  new ProjectHasUser();
                $projectHasUser->setProject($projectToAdd);
                $projectHasUser->setUser($userToAdd);
                $projectHasUser->setProjectRole($roleToAdd);

                $em = $this->getDoctrine()->getManager();
                $em->persist($projectHasUser);
                $em->flush();
                $this->addFlash('success', "User added to the project");
            } else {
                $this->addFlash('success', "User already has a role in this project");
            }
            return $this->redirectToRoute('add_user_to_project');
        }

        return $this->render('project/adduserstoproject.html.twig', array(
            "pageHeader" => "Project supervising",
            "subHeader" => "Add user to the project",
            "form" => $form->createView()
        ));
    }

    public function removeAction(Project $project, User $user, Request $request)
    {

        return null;
    }
}
