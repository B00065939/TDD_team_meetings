<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectHasUser;
use AppBundle\Entity\User;
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
        $this->addFlash('success', "User removed from project");
        return $this->redirectToRoute('show_project', array('project' => $project->getId()));
    }

    /**
     * @Security("is_granted('ROLE_REMOVE_USER_FROM_PROJECT')")
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
            "pageHeader" => "Project supervising",
            "subHeader" => "Add user to the project",
            "form" => $form->createView()
        ));
    }


}
