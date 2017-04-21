<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProjectRole;
use AppBundle\Form\NewRoleForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RoleController extends Controller
{
    /**
     * @Security("is_granted('ROLE_NEW_PROJECT_ROLE')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $roles = $em->getRepository(ProjectRole::class)->findAll();

        $form = $this->createForm(NewRoleForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var ProjectRole $projectRole
             */
            $projectRole = $form->getData();
            foreach ($roles as $item) {
                if ($item->getName() == $projectRole->getName()) {
                    $this->addFlash('success', "Project role already exists");
                    return $this->redirectToRoute('new_project_role');
                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectRole);
            $em->flush();
            $this->addFlash('error', "Project role was added");
            return $this->redirectToRoute('new_project_role');
        }
        return $this->render('role/newrole.html.twig', array(
            "pageHeader" => "Project supervising",
            "subHeader" => "Creater new Project Role",
            "roles" => $roles,
            "form" => $form->createView()

        ));
    }

    /**
     * @Security("is_granted('ROLE_DELETE_PROJECT_ROLE')")
     * @param Request $request
     * @param ProjectRole $projectRole
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, ProjectRole $projectRole)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectRole);
        $em->flush();

        $this->addFlash('success', "Project role was deleted");
        return $this->redirectToRoute('new_project_role');
    }
}
