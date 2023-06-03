<?php

namespace App\Controller;

use App\Repository\DoctorsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctorsController extends AbstractController
{
    #[Route('/doctors', name: 'app_doctors')]
    public function index(DoctorsRepository $doctorsRepository): Response
    {

        $user = $this->getUser();
        if(!$user){
            throw $this->createAccessDeniedException('Accès refusé');
        }
        // fetch user data
        $data = [];

        $roles = $this->getUser()->getRoles();

        foreach ($roles as $role){
            $data[] = $role;
        }

        // checks if the view match with role

        $doctorView = false;

        if(in_array('ROLE_DOCTOR', $roles)){
            $doctorView = true;

        }




        return $this->render('doctors/index.html.twig', [
            'role'=> $data,
            'doctorView'=> $doctorView,
            'user'=> $user,
//            'doctor'=> $doctor
        ]);
    }
}
