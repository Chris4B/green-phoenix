<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use http\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Test\ConstraintViolationAssertion;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class WelcomeController extends AbstractController
{
    #[Route('/', name: 'app_welcome_index')]
    public function index(Request $request, UsersRepository $usersRepository, ValidatorInterface $validator): Response
    {

        $submit = $request->request->get('submit');
        $search = $request->request->get('doctor_search');

        // validation of data
        $errors = $validator->validate($search, [new NotBlank([
            'message' => 'Veuillez entrer un terme de recherche.',
        ])
        ]);

        if(count($errors) === 0){
            $doctors = $usersRepository->findByFirstName($search);
            if(!empty($doctors)){
                return $this->render('welcome/index.html.twig', [
                    'medecins' => $doctors,
                    'prenom' => $search
                ]);

            }
        }

        return $this->render('welcome/index.html.twig', [
            'errors' => $errors,
            'search'=> $search,
        ]);
    }
}
