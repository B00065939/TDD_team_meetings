<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\NewUserForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function adminPanelAction()
    {
        if (($this->getUser() != null) && ($this->getUser()->getRoles() == ['ROLE_ADMIN'])) {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('AppBundle:User')->findAll();

            return $this->render(
                'admin/adminpanel.html.twig',[
                "users" => $users
                    ]);
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Security("is_granted('ROLE_NEW_USER')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newUserAction(Request $request)
    {
        $form = $this->createForm(NewUserForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var User $user
             */
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', "User added");
            return $this->redirectToRoute('new_user');
        }

        return $this->render('admin/newuser.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
