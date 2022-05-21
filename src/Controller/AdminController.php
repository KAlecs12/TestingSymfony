<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\User;
use App\Form\AddFileFormType;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use App\Services\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/')]
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function new(Request $request, FileUploader $fileUploader, ManagerRegistry $doctrine,  UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $facture = new Facture();
        $form = $this->createForm(AddFileFormType::class, $user);
        $form2 = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $form2->handleRequest($request);

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $uploadedFile */
                $uploadedFile = $form->get('imageFilename')->getData();
                $userc = $form->get('firstName')->getData();
                $user = $userc;

                if ($uploadedFile) {
                    $uploadedFileName = $fileUploader->upload($uploadedFile);
                    $facture->setIdUser($user);
                    $facture->setTitre(date('D, F, Y'));
                    $facture->setContent('/public/uploads/'.$uploadedFileName);


                    $entityManager = $doctrine->getManager();
                    $entityManager->persist($facture);
                    $entityManager->flush();
                }

                return $this->redirectToRoute('app_admin');
            } else if ($form2->isSubmitted() && $form2->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form2->get('plainPassword')->getData()
                    )
                );

                $entityManager = $doctrine->getManager();
                $entityManager->persist($user);
                $entityManager->flush();


                return $this->redirectToRoute('app_admin');
            }

        return $this->renderForm('admin/admin.html.twig', [
            'form' => $form,
            'form2' => $form2
        ]);
    }

}
