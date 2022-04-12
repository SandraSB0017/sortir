<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="app_sortie")
     */
    public function index(): Response
    {
        return $this->render('sortie/index.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * @Route("/creation", name="sortie_creation")
     */
    public function creation()
    {
        return $this->render('sortie/creation.html.twig');
    }

    /**
     * @Route("/annulation", name="sortie_annulation")
     */
    public function annulation()
    {
        return $this->render('sortie/annulation.html.twig');
    }
}
