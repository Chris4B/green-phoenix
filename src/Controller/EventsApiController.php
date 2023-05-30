<?php

namespace App\Controller;

use App\Entity\Doctors;
use App\Entity\Events;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EventsApiController extends AbstractController
{
    #[Route('/api/events', name: 'app_events_api')]
    public function index(EventsRepository $eventsRepository): JsonResponse
    {
        $user = $this->getUser();

        // retrieving events from specific user
        $events = $eventsRepository->findBy(['users' => $user]);

        $data = [];

        foreach ($events as $event) {

            $data[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'datestr' => $event->getDateString()
            ];
        };

        return $this->json($data);
    }


    #[Route('/api/events/new', name: 'app_events_api_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {


        if ($request->isMethod('POST') && (!empty($request->getContent()))){


            //decoding JSON
            $data = json_decode($request->getContent(), true);
            $doctorId = $request->query->all();

            //Checking data

            if(!isset($data['title'])||!isset($data['datestr'])){
                return new JsonResponse(['error' => 'Les données requises sont manquantes', 400]);
            }


            $user = $this->getUser();
//            $doctor = $entityManager->getRepository(Doctors::class)->find($doctorsId);
            //creating new event and persisting

            $event = new Events();
            $event->setTitle($data['title']);
            $event->setDateString($data['datestr']);
            $event->setUsers($user);
//            $event->setDoctors($doctor);

            $entityManager->persist($event);
            $entityManager->flush();

            // succeed message

            $response = [
                'id'=>$event->getId(),
                'title' => $event->getTitle(),
                'datestr'=> $event->getDateString()

            ];

//            return new JsonResponse(['message' => "l'événement a été créé avec succès"], 201);
            return $this->json($response);


        }

        return new JsonResponse(['error' => 'Méthode non autorisée ou données manquantes']);

    }


    #[Route('/api/events/update/{id}', name: 'app_events_api_update', requirements: ['id'=>'\d+'])]
    public function update(Request $request, EventsRepository $eventsRepository,EntityManagerInterface $entityManager,$id):JsonResponse
    {
        $user = $this->getUser();

        //retrieve user by association of eventID and UserID

        $event = $entityManager->getRepository(Events::class)->findOneBy(['id'=>$id,'users'=>$user]);

        // check if the event id exist
        if (!$event) {
            return new JsonResponse(['error' => 'L\'événement n\'existe pas'], 404);
        }

        if ($request->isMethod('POST') && (!empty($request->getContent()))){

            //decoding JSON
            $data = json_decode($request->getContent(), true);
//            dd($data);

            //Checking data

            if(!isset($data['title'])||!isset($data['datestr'])){
                return new JsonResponse(['error' => 'Les données requises sont manquantes', 400]);
            }

            //updating event

            $event->setTitle($data['title']);
            $event->setDateString($data['datestr']);

            $entityManager->flush();

            // succeed message

            $response = [
                'title' => $event->getTitle(),
                'datestr'=> $event->getDatestring()
            ];

            return new JsonResponse(['message' => "l'événement a été créé avec succès"], 201);
//            return $this->json($response);



        }

        return new JsonResponse(['error' => 'Méthode non autorisée ou données manquantes']);
    }

    #[Route('/api/events/{id}', name: 'app_events_remove', methods: ['DELETE'])]
    public function remove(Request $request, Events $event, EventsRepository $eventsRepository, $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $event = $eventsRepository->find($id);


        if (!$event) {
            return new JsonResponse(['error' => 'Event not found'], Response::HTTP_NOT_FOUND);
        }

//        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
//            $eventsRepository->remove($event, true);
//            return new JsonResponse(['message' => 'Event deleted'], Response::HTTP_OK);
//        }
        $entityManager->remove($event);
        $entityManager->flush();

//        return $this->redirectToRoute('app_events_index', [], Response::HTTP_SEE_OTHER);
        return new JsonResponse([$event->getId()], Response::HTTP_OK);
    }




}
