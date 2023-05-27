<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    #[Route('/', name: 'app_welcome_index')]
    public function index(Request $request, UsersRepository $usersRepository): Response
    {

        $submit = $request->request->get('submit');
        $search = $request->request->get (trim('doctor_search'));

        if(isset($submit) && !empty($search)){

            $doctors = $usersRepository->findByFirstName($search);



            if(!empty($doctors)){
                return $this->render('welcome/index.html.twig', [
                    'medecins' => $doctors,
                    'prenom' => $search
                ]);

            }

        }
        return $this->render('welcome/index.html.twig', [

        ]);
    }
}
