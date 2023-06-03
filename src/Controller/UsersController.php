<?php

namespace App\Controller;

use App\Repository\EventsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    public function index(EventsRepository $eventsRepository): Response
    {

        $user = $this->getUser();
        if(!$user){
            throw $this->createAccessDeniedException('Accès refusé');
        }

        // fetch user Roles
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

        $events = $eventsRepository->findBy(['users'=>$user]);
//        $events = $user->getEvents();

        return $this->render('users/index.html.twig', [
            'role'=> $data,
            'doctorView'=> $doctorView,
            'user'=> $user,
            'events'=>$events

        ]);
    }
}
