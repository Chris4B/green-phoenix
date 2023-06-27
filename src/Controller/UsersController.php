<?php

namespace App\Controller;

use App\Repository\DoctorsRepository;
use App\Repository\EventsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    public function index(EventsRepository $eventsRepository, DoctorsRepository $doctorsRepository): Response
    {

        $user = $this->getUser();
        if(!$user){
            throw $this->createAccessDeniedException('Accès refusé');
        }

////      associated doctors
//        $doctors = $user->getDoctors();
//
//
//
////        $events = $eventsRepository->findBy(['users'=>$user]);
        $events = $user->getEvents();


        return $this->render('users/index.html.twig', [

//            'doctor'=> $doctors,
//            'user'=> $user,
            'events'=>$events,

        ]);
    }
}
