<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectHasUser;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\User;
use AppBundle\Form\NewProjectForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    public function showAction(Request $request, Project $project)
    {

        $em = $this->getDoctrine()->getManager();
        $roleSecretary = $em->getRepository(ProjectRole::class)->findOneBy(['name'=>"Project Secretary"]);

        $secretary = $em->getRepository(ProjectHasUser::class)->findOneBy([
            'project' => $project,
            'projectRole' => $roleSecretary
        ]);

        $roleLeader = $em->getRepository(ProjectRole::class)->findOneBy(['name'=>"Project Leader"]);
        $leader = $em->getRepository(ProjectHasUser::class)->findOneBy([
            'project' => $project,
            'projectRole' => $roleLeader
        ]);
        $roleSupervisor = $em->getRepository(ProjectRole::class)->findOneBy(['name'=>"Project Supervisor"]);
        $supervisor = $em->getRepository(ProjectHasUser::class)->findOneBy([
            'project' => $project,
            'projectRole'=>$roleSupervisor
        ]);

        $projectHasUsersList = $em->getRepository(ProjectHasUser::class)->findBy(['project'=>$project]);

        return $this->render('project/project.html.twig', array(
            "pageHeader" => "Project supervising",
            "subHeader" => "Project ". $project->getTitle() . " details.",
            "project" => $project,
            "secretary" => $secretary,
            "leader" => $leader,
            "supervisor" => $supervisor,
            "projectHasUsersList" => $projectHasUsersList
        ));
    }

    public function testAction()
    {
    }

    /**
     * Locking or unlocking project
     * @param Request $request
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function turnLockAction(Request $request, Project $project)
    {
        if ($project->isLock()) {
            $project->setLock(false);
        } else {
            $project->setLock(true);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();
        return $this->redirectToRoute('homepage');
    }

    /**
     * Delete project (not used to many relations between replace by lock project)
     * @param Request $request
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();

        $this->addFlash('success', "Project was deleted");
        return $this->redirectToRoute('homepage');
    }


    /**
     * Create new project
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse |\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
//        dump("jeblo");
//        die();

        $form = $this->createForm(NewProjectForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /**
             * @var Project $project
             */
            $project = new Project();
            $project->setTitle($form->get('title')->getData());
            $project->setLock($form->get('lock')->getData());
            $em->persist($project);

            /**
             * @var User $projectLeader
             */
            $projectLeader = new User();
            $projectLeader = $form->get('projectLeader')->getData();

            /**
             * @var User $projectSecretary
             */
            $projectSecretary = new User();
            $projectSecretary = $form->get('projectSecretary')->getData();

            /**
             * @var ProjectHasUser $projectHasUser
             */
            $projectHasUser = new ProjectHasUser();
            $projectHasUser->setProject($project);
            $projectHasUser->setProjectRole($em->getRepository(ProjectRole::class)->findOneBy(['name' => 'Project Leader']));
            $projectHasUser->setUser($projectLeader);
            $em->persist($projectHasUser);

            $projectHasUser = new ProjectHasUser();
            $projectHasUser->setProject($project);
            $projectHasUser->setProjectRole($em->getRepository(ProjectRole::class)->findOneBy(['name' => 'Project Secretary']));
            $projectHasUser->setUser($projectSecretary);
            $em->persist($projectHasUser);

            // add project creator(current user)
            $projectHasUser = new ProjectHasUser();
            $projectHasUser->setProject($project);
            $projectHasUser->setProjectRole($em->getRepository(ProjectRole::class)->findOneBy(['name' => 'Project Supervisor']));
            $projectHasUser->setUser($this->getUser());
            $em->persist($projectHasUser);

            $em->flush();

            $this->addFlash('success', "Project was crated");
            return $this->redirectToRoute('new_project');
        }
        return $this->render('project/newproject.html.twig', array(
            "pageHeader" => "Project supervising",
            "subHeader" => "Create new Project",
            "form" => $form->createView()

        ));
    }
}
