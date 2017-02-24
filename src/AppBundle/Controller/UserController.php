<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 23/02/2017
 * Time: 21:35
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\NewUserForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
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
        if($form->isSubmitted() && $form->isValid()){
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