<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 23/02/2017
 * Time: 21:35
 */

namespace AppBundle\Controller;


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
        return $this->render('admin/newuser.html.twig', [
            'form' => $form->createView()
        ]);
    }

}