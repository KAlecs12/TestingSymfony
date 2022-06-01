<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Calendar;
use App\Entity\Facture;
use App\Entity\User;
use App\Form\AddArticleType;
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

        $events = $calendarRepository->findBy(array('deleted' => 0));

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
                'dispo' => $event->getDispo(),
            ];
        }

        $data = json_encode($rdvs);


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
                $calendaradd->setDispo(0);
                $calendaradd->setDeleted(0);
            }
            else{
                $calendaradd->setBackgroundColor("#9AC8EB");
                $calendaradd->setBorderColor("#212E53");
                $calendaradd->setTextColor("#000000");
                $calendaradd->setDispo(0);
                $calendaradd->setDeleted(0);
            }

            $entityManager = $doctrine->getManager();
            $entityManager->persist($calendaradd);
            $entityManager->flush();

            $this->addFlash('success', 'Rendez-vous ajouté avec succès.');

            return $this->redirectToRoute('app_rdv', [], Response::HTTP_SEE_OTHER);
        }

        $user = $this->getUser();

        return $this->renderForm('rdv/rdv.html.twig', [
            'data' => $data,
            'form' => $form,
            'user' => $user

        ]);
    }

    #[Route('rdv/get/', name: 'app_getrdv')]
    public function getrdv(Request $request,EntityManagerInterface $entityManager): Response
    {
        // On recupere la variable myData depuis le script Jquery dans le twig rdv et on envoie le user dans le rendez-vous
        $data = isset($_REQUEST['myData'])?$_REQUEST['myData']:"";
        $calendar = $entityManager
            ->getRepository(Calendar::class)
            ->find($data);

            $user = $this->getUser();
            $calendar->setidUser($user);
            $calendar->setbackgroundColor("#B22222");
            $calendar->setDispo(1);
            // On recup le titre pour l'afficher dans le flash
            $titre = $calendar->getTitle();

            $entityManager->persist($calendar);
            $entityManager->flush();

            $this->addFlash('success', 'Rendez-vous pour ' . $titre . ' accepté.');

        return $this->redirectToRoute('app_rdv');
    }

    #[Route('rdv/delete/', name: 'app_deleterdv')]
    public function deleterdv(EntityManagerInterface $entityManager): Response
    {
        $data = isset($_REQUEST['myData'])?$_REQUEST['myData']:"";

        $calendar = $entityManager
            ->getRepository(Calendar::class)
            ->find($data);

        // On recup le titre pour l'afficher dans le flash
        $titre = $calendar->getTitle();

        $calendar->setDeleted(1);
        $entityManager->persist($calendar);
        $entityManager->flush();

        $this->addFlash('success', 'Rendez-vous pour '.$titre.' supprimé.');

        return $this->redirectToRoute('app_rdv');
    }

}
