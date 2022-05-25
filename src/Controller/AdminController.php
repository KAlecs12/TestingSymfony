<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\User;
use App\Form\AddFileFormType;
use App\Form\RegistrationFormType;
use App\Form\SetPasswordFormType;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

#[Route('/')]
class AdminController extends AbstractController
{
    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    #[Route('/admin', name: 'app_admin')]
    public function new(MailerInterface $mailer, Request $request, FileUploader $fileUploader, ManagerRegistry $doctrine, VerifyEmailHelperInterface $verifyEmailHelper): Response
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

                $entityManager = $doctrine->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $signatureComponents = $verifyEmailHelper->generateSignature(
                    'app_verify_email',
                    $user->getId(),
                    $user->getEmail(),
                    ['id' => $user->getId()]
                );

                $email = (new Email())
                    ->from('onlylanelol@gmail.com')
                    ->to($user->getEmail())
                    ->subject('Bienvenue a l\'ecurie persevere !')
                    ->text("\nRavie de vous avoir parmis nous {$user->getFirstName()}! ❤️ \n". $signatureComponents->getSignedUrl());

                $mailer->send($email);

                $this->addFlash('success', 'Compte créé, email envoyé.');

                return $this->redirectToRoute('app_admin');
            }

        return $this->renderForm('admin/admin.html.twig', [
            'form' => $form,
            'form2' => $form2
        ]);
    }

    /**
     * @Route("/verify", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, VerifyEmailHelperInterface $verifyEmailHelper, UserRepository $userRepository, ManagerRegistry $doctrine,  UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $userRepository->find($request->query->get('id'));
        if (!$user) {
            throw $this->createNotFoundException();
        }
        try {
            $verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                $user->getId(),
                $user->getEmail(),
            );
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('error', $e->getReason());
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(SetPasswordFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('app_profile');
        }


        return $this->renderForm('set_password/pwd.html.twig', [
            'form' => $form,

        ]);
    }

}
