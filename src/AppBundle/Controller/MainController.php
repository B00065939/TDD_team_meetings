<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 09/02/2017
 * Time: 12:57
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepageAction() {
        return $this->render('index.html.twig');
    }


}