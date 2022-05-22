<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Facture;
use App\Entity\User;
use App\Form\AddFileFormType;
use App\Form\GetRdvType;
use App\Repository\CalendarRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RdvController extends AbstractController
{

    #[Route('/rdv', name: 'app_rdv')]
    public function index(CalendarRepository $calendarRepository, Request $request, SluggerInterface $slugger, FileUploader $fileUploader, ManagerRegistry $doctrine): Response
    {

        $events = $calendarRepository->findAll();

        $rdvs = [];
        foreach ($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
            ];
        }

        $data = json_encode($rdvs);



        $calendar = new Calendar();
        $form = $this->createForm(GetRdvType::class, $calendar);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $calendar = $form->get('id')->getData();
            $rdv = $calendarRepository->find(array('id' => $calendar));
            $rdv->setIdUser($user);

                $entityManager = $doctrine->getManager();
                $entityManager->persist($rdv);
                $entityManager->flush();

            return $this->redirectToRoute('app_rdv');
        }

        return $this->renderForm('rdv/rdv.html.twig', [
            'data' => $data,
            'form' => $form,
        ]);

    }
}
