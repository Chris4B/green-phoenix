<?php

namespace App\Controller;


use App\Entity\Doctors;
use App\Entity\Users;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/patients/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator, CsrfTokenManagerInterface $csrfTokenManager, ): Response
    {
        if($request->isMethod('POST') && !empty($request->request->all())) {

            //Validation of the token
            $csrfToken = $request->request->get('_csrf_token');
            if(!$csrfTokenManager->isTokenValid( new CsrfToken('authenticate',$csrfToken))){
                throw new AccessDeniedException(" Jeton CSRF invalide.");
            }

            //Fetch data

            $firstName = trim($request->request->get('firstName'));
            $lastName = trim($request->request->get('lastName'));
            $email = trim($request->request->get('email'));
            $password = trim($request->request->get('password'));

            //create a new user and hydrating data
            $user = new Users();
            $user->setFirstName($firstName);
            $user->setLastName(($lastName));
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);


            // Validation of data

            $errors = $validator->validate($user);

            if (count($errors) > 0) {
                //handle validation errors
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
                return $this->render('registration/register.html.twig', [
                    'errors' => $errorMessages
                ]);
            }
            // Haching password
            $user->setPassword($userPasswordHasher->hashPassword($user, $password));
            //Saving in the database
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig');
    }

    #[Route('/docteurs/inscription', name: 'app_doctor_register')]
    public function DoctorRegister(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        if($request->isMethod('POST') && !empty($request->request->all())) {

            //Validation of the token
            $csrfToken = $request->request->get('_csrf_token');
            if(!$csrfTokenManager->isTokenValid( new CsrfToken('authenticate',$csrfToken))){
                throw new AccessDeniedException(" Jeton CSRF invalide.");
            }

            //Fetching data

            $firstName = trim($request->request->get('firstName'));
            $lastName = trim($request->request->get('lastName'));
            $email = trim($request->request->get('email'));
            $password = trim($request->request->get('password'));

            //create a new doctor and hydrating data
            $doctor = new Doctors();
            $doctor->setFirstName($firstName);
            $doctor->setLastName(($lastName));
            $doctor->setEmail($email);
            $doctor->setPassword($password);
            $doctor->setRoles(['ROLE_DOCTOR']);
            $doctor->setSpeciality('Medecin');


            // Validation of data

            $errors = $validator->validate($doctor);

            if (count($errors) > 0) {
                //handle validation errors
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
                return $this->render('registration/doctor_register.html.twig', [
                    'errors' => $errorMessages
                ]);
            }
            // Haching password
            $doctor->setPassword($userPasswordHasher->hashPassword($doctor, $password));
            //Saving in the database
            $entityManager->persist($doctor);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/doctor_register.html.twig');

    }
}
