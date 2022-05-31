<?php

namespace App\Controller;


use App\Entity\Cheval;
use App\Entity\Contact;
use App\Entity\Facture;
use App\Entity\User;
use App\Form\AddFileFormType;
use App\Form\AddHorseType;
use App\Form\EditHorseType;
use App\Form\LinkHorseType;
use App\Form\RegistrationFormType;
use App\Form\SetPasswordFormType;
use App\Form\SupprUserType;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

#[Route('/')]
class AdminController extends AbstractController
{
    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    #[Route('/admin', name: 'app_admin')]
    public function new(EntityManagerInterface $entityManager, MailerInterface $mailer, Request $request, FileUploader $fileUploader, ManagerRegistry $doctrine, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $user = new User();
        $facture = new Facture();
        $cheval = new Cheval();
        $form = $this->createForm(AddFileFormType::class, $user);
        $form2 = $this->createForm(RegistrationFormType::class, $user);
        $form3 = $this->createForm(AddHorseType::class, $cheval);
        $form4 = $this->createForm(LinkHorseType::class, $cheval);
        $form5 = $this->createForm(EditHorseType::class, $cheval);
        $form6 = $this->createForm(SupprUserType::class, $user);
        $form->handleRequest($request);
        $form2->handleRequest($request);
        $form3->handleRequest($request);
        $form4->handleRequest($request);
        $form5->handleRequest($request);
        $form6->handleRequest($request);

//Pour l'envoie du PDF a un Utilisateur

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            //on recupere le submit
            $uploadedFile = $form->get('imageFilename')->getData();
            $userc = $form->get('id')->getData();
            $user = $userc;

            //Ajout de son nom dans la BDD et de son path
            if ($uploadedFile) {

                $uploadedFileName = $fileUploader->upload($uploadedFile);
                $facture->setIdUser($user);
                $facture->setTitre(date('D, F, Y'));
                $facture->setContent('/public/uploads/' . $uploadedFileName);

                //envoie dans la BDD
                $entityManager = $doctrine->getManager();
                $entityManager->persist($facture);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_admin');

// Creation d'un user, en envoyant un mail a la personne pour qu'elle ajoute son mdp
        } else if ($form2->isSubmitted() && $form2->isValid()) {

            $user->setStatus("ok");

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $signatureComponents = $verifyEmailHelper->generateSignature(
                'app_verify_email',
                $user->getId(),
                $user->getEmail(),
                ['id' => $user->getId()]
            );

            // Contenu du mail
            $email = (new Email())
                ->from('ecurie.persevere.uf@gmail.com')
                ->to($user->getEmail())
                ->subject('Bienvenue a l\'ecurie persevere !')
                ->text("\n\nRavie de vous avoir parmis nous {$user->getFirstName()}! ❤️ \n\nCliquez sur le lien afin de créer votre mot de passe : " . $signatureComponents->getSignedUrl());

            $mailer->send($email);

            $this->addFlash('success', 'Compte créé, email envoyé.');

            return $this->redirectToRoute('app_admin');

// Ajout d'un cheval
        } else if ($form3->isSubmitted() && $form3->isValid()) {

            $cheval->setStatus("ok");
            $entityManager = $doctrine->getManager();
            $entityManager->persist($cheval);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin');

//Lier un cheval a un client
        } else if ($form4->isSubmitted() && $form4->isValid()) {

            $cheval = $entityManager
                ->getRepository(Cheval::class)
                ->find($form4->get('nom')->getData());

            $userc = $form4->get('id')->getData();
            $user = $userc;

            //Ajout de son nom dans la BDD et de son path
            if ($cheval) {
                $cheval->setIdUser($user);

                //envoie dans la BDD
                $entityManager = $doctrine->getManager();
                $entityManager->persist($cheval);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_admin');
// Modifier un Cheval
        } else if ($form5->isSubmitted() && $form5->isValid()) {

            $cheval = $entityManager
                ->getRepository(Cheval::class)
                ->find($form5->get('id')->getData());

            $nom = $form5->get('nom')->getData();
            $box = $form5->get('box')->getData();


            //Ajout de son nom dans la BDD et de son path
            if ($nom != null) {
                $cheval->setNom($nom);
            }
            if ($box != null) {
                $cheval->setBox($box);
            }

            //envoie dans la BDD
            $entityManager = $doctrine->getManager();
            $entityManager->persist($cheval);
            $entityManager->flush();

        return $this->redirectToRoute('app_admin');

//Supprimer un client
    } else if ($form6->isSubmitted() && $form6->isValid()) {

            $user = $entityManager
                ->getRepository(User::class)
                ->find($form6->get('id')->getData());

            $user->setStatus("suppr");
                //suppr dans la BDD
                $entityManager = $doctrine->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

            return $this->redirectToRoute('app_admin');
        }

// On recupere tous les chevaux pour les affichers si ils sont presents
        $chevaux = $entityManager
            ->getRepository(Cheval::class)
            ->findBy(array('status' => "ok"));

        return $this->renderForm('admin/admin.html.twig', [
            'form' => $form,
            'form2' => $form2,
            'form3' => $form3,
            'form4' => $form4,
            'form5' => $form5,
            'form6' => $form6,
            'chevaux' => $chevaux
        ]);
    }


    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete($id,EntityManagerInterface $entityManager): Response
    {

        $cheval = $entityManager
            ->getRepository(Cheval::class)
            ->find($id);

        $cheval->setStatus("suppr");
        $entityManager->persist($cheval);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin');
    }


    /**
     * @Route("/verify", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, VerifyEmailHelperInterface $verifyEmailHelper, UserRepository $userRepository, ManagerRegistry $doctrine,  UserPasswordHasherInterface $userPasswordHasher): Response
    {

        // page ajout de mdp, sécurisé

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


            return $this->redirectToRoute('app_login');
        }


        return $this->renderForm('set_password/pwd.html.twig', [
            'form' => $form,

        ]);
    }

    #[Route('/admin/users/list', name: 'app_userlist')]
    public function userslist(EntityManagerInterface $entityManager, Request $request, UserRepository $repo): Response
    {

// On recupere tous les users pour les affichers si ils sont presents
        $query = $request->request->all('form')['query'];
        if($query) {
            $user = $repo->findUsersByName($query);
        }

        $users = $entityManager
            ->getRepository(User::class)
            ->findBy(array('status' => "ok"));

        return $this->render('admin/users.html.twig', [
            'user' => $user,
            'users' => $users
        ]);
    }

    public function searchBar(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Recherche...'
                ]
            ])
            ->add('recherche', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            return $this->redirectToRoute('app_userlist');
        }
 
        
        return $this->render('admin/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
