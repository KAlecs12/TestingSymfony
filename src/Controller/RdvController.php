<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Facture;
use App\Entity\User;
use App\Form\AddFileFormType;
use App\Form\CalendarType;
use App\Form\GetRdvType;
use App\Repository\CalendarRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index( CalendarRepository $calendarRepository, Request $request, ManagerRegistry $doctrine): Response
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

        //choisir un rdv pour soi-meme
        $calendar = new Calendar();
        $form2 = $this->createForm(GetRdvType::class, $calendar);
        $form2->handleRequest($request);


        if ($form2->isSubmitted() && $form2->isValid()) {

            $user = $this->getUser();
            $calendar = $form2->get('id')->getData();
            $rdv = $calendarRepository->find(array('id' => $calendar));
            $rdv->setIdUser($user);

                $entityManager = $doctrine->getManager();
                $entityManager->persist($rdv);
                $entityManager->flush();

            return $this->redirectToRoute('app_rdv');
        }

        // ajouter rapidement un rdv, pour l'admin
        $calendaradd = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendaradd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $choice = $form->get('type')->getData();

            //set une couleur en fonction du type de l'evenement.
            if ($choice == "pro"){
                $calendaradd->setBackgroundColor("#7AA95C");
                $calendaradd->setBorderColor("#18534F");
                $calendaradd->setTextColor("#000000");
            }
            else{
                $calendaradd->setBackgroundColor("#9AC8EB");
                $calendaradd->setBorderColor("#212E53");
                $calendaradd->setTextColor("#000000");
            }

            $entityManager = $doctrine->getManager();
            $entityManager->persist($calendaradd);
            $entityManager->flush();

            return $this->redirectToRoute('app_rdv', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rdv/rdv.html.twig', [
            'data' => $data,
            'form' => $form,
            'form2' => $form2
        ]);

    }

}
