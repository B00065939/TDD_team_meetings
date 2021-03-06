<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProjectHasUser;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\User;
use AppBundle\Form\LoginForm;
use AppBundle\Repository\ProjectHasUserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class MainController
 * @package AppBundle\Controller
 */
class MainController extends Controller
{
    public function aboutAction()
    {
        return $this->render('about/about.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepageAction()
    {

        if (($this->getUser() != null) && ($this->getUser()->getRoles() == ['ROLE_USER'])) {
            return $this->redirectToRoute('user_panel');
        }
        if (($this->getUser() != null) && ($this->getUser()->getRoles() == ['ROLE_SUPERVISOR'])) {
            return $this->redirectToRoute('sup_panel');
        }
        if (($this->getUser() != null) && ($this->getUser()->getRoles() == ['ROLE_ADMIN'])) {
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

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function supPanelAction()
    {
        if (($this->getUser() != null) && ($this->getUser()->getRoles() == ['ROLE_SUPERVISOR'])) {
            $em = $this->getDoctrine()->getManager();

            $role = $em->getRepository(ProjectRole::class)->findOneBy(['name' => "Project Supervisor"]);

            $projects = $em->getRepository('AppBundle:ProjectHasUser')->findBy([
                'user' => $this->getUser(),
                'projectRole' => $role
            ]);

            return $this->render(
                'sup/suppanel.html.twig', [
                "pageHeader" => "Supervisor Panel",
                "subHeader" => "Project List",
                "projects" => $projects
            ]);
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userPanelAction()
    {
        if (($this->getUser() != null) && ($this->getUser()->getRoles() == ['ROLE_USER'])) {
            $em = $this->getDoctrine()->getManager();
            /**
             * @var User $user
             */
            $user = new User();
            $user = $this->getUser();
            $projects = $em->getRepository(ProjectHasUser::class)->findBy(['user' => $user]);
            return $this->render(
                'user/userpanel.html.twig', [
                    "pageHeader" => "User Panel",
                    "subHeader" => "Project List",
                    "projects" => $projects
                ]
            );
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminPanelAction()
    {
        if (($this->getUser() != null) && ($this->getUser()->getRoles() == ['ROLE_ADMIN'])) {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('AppBundle:User')->findAll();

            return $this->render(
                'admin/adminpanel.html.twig', [
                "users" => $users,
                "pageHeader" => "Admin Panel",
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