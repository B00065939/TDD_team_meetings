<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 09/02/2017
 * Time: 12:57
 */

namespace AppBundle\Controller;


use AppBundle\Form\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepageAction() {


        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class , [
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
}