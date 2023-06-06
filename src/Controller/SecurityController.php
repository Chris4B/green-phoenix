<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request, CsrfTokenManagerInterface $csrfTokenManager, Security $security, UrlGeneratorInterface $urlGenerator): Response
    {

//        if ($this->getUser()) {
//            $roles = $this->getUser()->getRoles();
//            if (in_array('ROLE_DOCTOR', $roles)) {
//                return $this->redirectToRoute('app_doctors');
//            } else {
//                return $this->redirectToRoute('app_users');
//            }
//        }
        $redirect = $request->query->get('redirect');

        if($security->isGranted('ROLE_USER')){
            return $this->redirectToRoute('app_users');
        }elseif($security->isGranted('ROLE_DOCTOR')){
            return $this->redirectToRoute('app_doctors');
        } elseif($redirect){
            return $this->redirect($redirect);
        }



        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
