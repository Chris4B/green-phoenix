<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Users;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event/users')]
class EventUsersController extends AbstractController
{
    #[Route('/', name: 'app_event_users_index', methods: ['GET'])]
    public function index(EventsRepository $eventsRepository): Response
    {
        return $this->render('event_users/index.html.twig', [
            'events' => $eventsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_event_users_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

            //retrieve all the data from the request
        $requestData = json_decode($request->getContent(), true);
        $appointmentData = $requestData['datestr'];
        $doctorId = $requestData['doctorId'];


            //findUserID
        $userId = $entityManager->getRepository(Users::class)->find($user->getId());


        $doctor = $entityManager->getRepository(Users::class)->find($doctorId);

            //Create a new instance of the Events entity
            $event = new Events();

            // set the properties of the events
            $event->setTitle($user->getFirstName().''.$user->getLastName());
            $event->setDateString($appointmentData);
            $event->setUsers($userId);
            $event->setDoctors($doctor);

            $entityManager->persist($event);
            $entityManager->flush();

            // link patient and doctor
        $doctor->addUser($user);
        $user->addDoctor($doctor);
//
//        dd($doctor->addUser($user));

        // JSON response
        $response = [
            'message' => 'Le rendez-vous a été enregistré avec succès.',
            'event' => $event,
        ];

        return new Response(json_encode($response), Response::HTTP_OK, ['Content-Type' => 'application/json']);
//        return  $this->redirectToRoute('app_users');

    }

    #[Route('/{id}', name: 'app_event_users_show', methods: ['GET'])]
    public function show(Events $event): Response
    {
        return $this->render('event_users/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();

        //get the modified Data form the request

        $requestData = json_decode($request->getContent(), true);
        $dateString = $requestData('datestr');
        $idEvent = $requestData['eventId'];


        // Retrieve the event by ID
//        $event = $eventsRepository->find($idEvent);
        $event = $entityManager->getRepository(Events::class)->findOneBy($idEvent);

//        if (!$targetEvent) {
//            // Event not found
//            return $this->json(['message' => 'Événement non trouvé.'], Response::HTTP_NOT_FOUND);
//        }

        // update the event with the modified data
        $event->setDateString($dateString);

        //save the modified event
//        $entityManager->persist($event);
        $entityManager->flush();

        //Create the response Data
        $responseData = [
            'event'=>$event,
            'message'=> "L'événement a été modifié avec succès"
        ];

        //return Json
        return $this->json($responseData);



    }

    #[Route('/{id}', name: 'app_event_users_delete', methods: ['POST'])]
    public function delete(Request $request, Events $event, EventsRepository $eventsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $eventsRepository->remove($event, true);
        }

        return $this->redirectToRoute('app_event_users_index', [], Response::HTTP_SEE_OTHER);
    }
}
