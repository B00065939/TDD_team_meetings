<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectHasUser;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\User;
use AppBundle\Form\AddKeyUsersToProjectForm;
use AppBundle\Form\NewProjectForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{

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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {

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
            $em->flush();

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
            $em->flush();

            $projectHasUser = new ProjectHasUser();
            $projectHasUser->setProject($project);
            $projectHasUser->setProjectRole($em->getRepository(ProjectRole::class)->findOneBy(['name' => 'Project Secretary']));
            $projectHasUser->setUser($projectSecretary);
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
//    public function addKeyUsersToProjectAction(Request $request)
//    {
////        if ($projectLeader == null || $projectSecretary == null || $projectLeader == $projectSecretary) {
////
////            retutn ;
////        } else {
//        //User $projectLeader, User $projectSecretary
//        $form = $this->createForm(AddKeyUsersToProjectForm::class);
//
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            /**
//             * @var Project $project
//             */
//            $project = $form->getData();
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($project);
//            $em->flush();
//            $this->addFlash('success', "Project was crated");
//            return $this->redirectToRoute('new_project');
//        }
//        return $this->render('project/addusers.html.twig', array(
//            "pageHeader" => "Project supervising",
//            "subHeader" => "Create new Project",
//            "form" => $form->createView()
//
//        ));
//    }
//    }

}
