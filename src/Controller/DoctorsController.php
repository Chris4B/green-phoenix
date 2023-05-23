<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctorsController extends AbstractController
{
    #[Route('/doctors', name: 'app_doctors')]
    public function index(): Response
    {
        return $this->render('doctors/index.html.twig', [

        ]);
    }
}