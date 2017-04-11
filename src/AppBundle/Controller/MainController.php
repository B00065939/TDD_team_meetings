<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 09/02/2017
 * Time: 12:57
 */

namespace AppBundle\Controller;


use AppBundle\Entity\ProjectRole;
use AppBundle\Form\LoginForm;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepageAction()
    {

        //if($this->denyAccessUnlessGranted('ROLE_ADMIN')) {

        if (($this->getUser() != null) && ($this->getUser()->getRoles() == ['ROLE_USER'])) {
            return $this->redirectToRoute('user_panel');
        }
        if (($this->getUser() != null) && ($this->getUser()->getRoles() == ['ROLE_SUPERVISOR'])  ) {
            return $this->redirectToRoute('sup_panel');
        }
        if (($this->getUser() != null) && ($this->getUser()->getRoles() == ['ROLE_ADMIN'])  ) {
            return $this->redirectToRoute('admin_panel');
        }
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername,
        ]);

        return $this->render(
            'main/homepage.html.twig',
            array(

                'form' => $form->createView(),
                'error' => $error,
            )
        );
    }

    public function supPanelAction()
    {
        if (($this->getUser() != null) && ($this->getUser()->getRoles() == ['ROLE_SUPERVISOR'])) {
            $em = $this->getDoctrine()->getManager();

            $role = $em->getRepository(ProjectRole::class)->findOneBy(['name'=>"Project Supervisor"]);

            $projects = $em->getRepository('AppBundle:ProjectHasUser')->findBy([
                'user' => $this->getUser(),
                'projectRole' => $role
            ]);


            //findAllProjectsWhereUserHasRole($this->getUser(), $role);

            return $this->render(
                'sup/suppanel.html.twig',[
                "pageHeader" => "Project supervising",
                "subHeader" => "Project List",
                "projects" => $projects
            ]);
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

    public function userPanelAction()
    {
        if (($this->getUser() != null) && ($this->getUser()->getRoles() == ['ROLE_USER'])) {
            return $this->render(
                'user/userpanel.html.twig',[
                    "pageHeader" => "User Panel",
                    "subHeader" => "Project List"
                ]
            );
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

    public function adminPanelAction()
    {
        if (($this->getUser() != null) && ($this->getUser()->getRoles() == ['ROLE_ADMIN'])) {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('AppBundle:User')->findAll();

            return $this->render(
                'admin/adminpanel.html.twig', [
                "users" => $users,
                "pageHeader" => "Administrator Panel",
                "subHeader" => "User List"
            ]);
        } else {
            return $this->redirectToRoute('homepage');
        }
    }
    /**
     * @throws Exception
     */
    public function logoutAction()
    {
        throw new Exception('this should not be reached!');
    }
}