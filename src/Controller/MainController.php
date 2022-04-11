<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="app_loginPage")
     */
    public function login():Response
    {
        return $this->render('main/login.html.twig');
    }



    /**
     * @Route("/accueil", name="app_accueil")
     */
    public function accueil(Request $request):Response
    {
        return $this->render('main/accueil.html.twig');
    }
}
