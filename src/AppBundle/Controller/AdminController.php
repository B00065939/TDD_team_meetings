<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\NewUserForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{

    /**
     * @Security("is_granted('ROLE_NEW_USER')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
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
            'form' => $form->createView(),
            "pageHeader" => "Administrator Panel",
            "subHeader" => "Create new user"
        ]);
    }

    /**
     * @Security("is_granted('ROLE_EDIT_USER')")
     * @param Request $request
     * @param $userID
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $userID)
    {

        $userToEdit = new User();
        $userToEdit = $this->getDoctrine()->getManager()->getRepository(User::class)->find($userID);
        $form  = $this->createForm(NewUserForm::class);
        $form ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var User $userFixed
             */
            $userFixed = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $userToEdit->setEmail($userFixed->getEmail());
            $userToEdit->setPlainPassword($userFixed->getPlainPassword());
            $userToEdit->setRoles($userFixed->getRoles());
            $em->persist($userToEdit);
            $em->flush();
            $this->addFlash('success', "User edited");
            return $this->redirectToRoute('homepage');
        }

        return $this->render('admin/edituser.html.twig', [
            'form' => $form->createView(),
            "pageHeader" => "Administrator Panel",
            "subHeader" => "Edit user",
            "userToEdit" => $userToEdit
        ]);
    }

    /**
     * @Security("is_granted('ROLE_DELETE_USER')")
     * @param Request $request
     * @param $userID
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, $userID)
    {
        $userToDelete = new User();
        $userToDelete = $this->getDoctrine()->getManager()->getRepository(User::class)->find($userID);

        $form = $this->createFormBuilder()
            ->add('id', HiddenType::class)
            ->getForm();
        //$action = $_POST[]
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $userToDelete = $em->getRepository(User::class)->find($userID);
            $em->remove($userToDelete);
            dump($userToDelete->getID());
            $em->flush();
            $this->addFlash('success', 'user was deleted');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('admin/deleteuser.html.twig', [
            "pageHeader" => "Administrator Panel",
            "subHeader" => "Delete user",
            "userToDelete" => $userToDelete,
            "form" => $form->createView()
        ]);
    }
}
