<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Cheval;
use App\Entity\Contact;
use App\Entity\Facture;
use App\Entity\User;
use App\Form\UpdateFormType;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    #[Route('/profil', name: 'app_profile')]
    public function index(EntityManagerInterface $entityManager, Request $request, KernelInterface $kernelInterface): Response
    {
        $factures = $entityManager
            ->getRepository(Facture::class)
            ->findBy(array('idUser' => $this->getUser()->getId()));

        $cheval = $entityManager
            ->getRepository(Cheval::class)
            ->findBy(array('idUser' => $this->getUser()->getId()));

        $rdv = $entityManager
            ->getRepository(Calendar::class)
            ->findBy(array('idUser' => $this->getUser()->getId()), array('id' => 'desc'),1,0);

        $contact = $entityManager
            ->getRepository(Contact::class)
            ->findBy(array('approuved' => "yes"), array('id_contact' => 'desc'),1,0);

        $contacts = $entityManager
            ->getRepository(Contact::class)
            ->findBy(array('idUser' => $this->getUser()->getId()));

        $user = $this->getUser();


        $form = $this->createForm(UpdateFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $prenom = $form->get('firstName')->getData();
            $nom = $form->get('lastName')->getData();
            $telephone = $form->get('Telephone')->getData();
            $adresse = $form->get('Adresse')->getData();
            $email = $form->get('email')->getData();

            if ($prenom == null) {
                $image = $user->getFirstName();
                $user->setFirstName($image);
            }
            if ($nom == null) {
                $image = $user->getLastName();
                $user->setLastName($image);
            }
            if ($telephone == null) {
                $image = $user->getImageFile();
                $user->setImageFile($image);
            }
            if ($adresse == null) {
                $image = $user->getAdresse();
                $user->setAdresse($image);
            }
            if ($email == null) {
                $image = $user->getEmail();
                $user->setEmail($image);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Les modifications ont été appliqué.');

            return $this->redirectToRoute('app_profile');
        }


        return $this->renderForm('profile/profile.html.twig', [
            'controller_name' => 'ProfileController',
            'factures' => $factures,
            'chevals' => $cheval,
            'rdv' => $rdv,
            'contact' => $contact,
            'contacts' => $contacts,
            'form' => $form,
        ]);
    }

}
