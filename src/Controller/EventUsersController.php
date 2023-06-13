<?php

namespace App\Controller;

use App\Entity\Events;
use App\Form\Events2Type;
use App\Repository\EventsRepository;
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
    public function new(Request $request, EventsRepository $eventsRepository): Response
    {
        $user = $this->getUser();

        //check if the request is POST request

        if($request->isMethod('POST') && $request->request->has('submit')){
            //retrieve all the data from the request
            $formData = $request->request->all();
            dd($formData);
            //Create a new instance of the Events entity
            $event = new Events();

            // set the properties of the events
            $event->setTitle($formData['title']);
            $event->setDateString($formData['date']);

        }





//        $event = new Events();
//        $form = $this->createForm(Events2Type::class, $event);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $eventsRepository->save($event, true);
//
//            return $this->redirectToRoute('app_event_users_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('event_users/new.html.twig', [
//            'event' => $event,
//            'form' => $form,
//        ]);
    }

    #[Route('/{id}', name: 'app_event_users_show', methods: ['GET'])]
    public function show(Events $event): Response
    {
        return $this->render('event_users/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Events $event, EventsRepository $eventsRepository): Response
    {
        $form = $this->createForm(Events2Type::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventsRepository->save($event, true);

            return $this->redirectToRoute('app_event_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event_users/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
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
