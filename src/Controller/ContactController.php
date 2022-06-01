<?php

namespace App\Controller;

use App\Entity\Cheval;
use App\Entity\Contact;
use App\Entity\User;
use App\Form\AddFileFormType;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contact')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'app_contact')]
    public function index(EntityManagerInterface $entityManager,  Request $request, ManagerRegistry $doctrine,): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();


            $pro = $form->get('idPro')->getData();

            $contact->setIdUser($user);
            $contact->setDeleted(0);
            $contact->setIdPro($pro);

                //envoie dans la BDD
                $entityManager = $doctrine->getManager();
                $entityManager->persist($contact);
                $entityManager->flush();


            return $this->redirectToRoute('app_profile');
        }

        return $this->renderForm('contact/contact.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/pro/', name: 'app_contactpro')]
    public function pro(EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser()->getId();

        $contacts = $entityManager
            ->getRepository(Contact::class)
            ->findBy(array('deleted' => 0,'idPro' => $userId));

        $role = $this->getUser()->getRoles();

        if ($role[0] == "ROLE_ADMIN"){
            $contacts = $entityManager
                ->getRepository(Contact::class)
                ->findBy(array('deleted' => 0));
        }


        return $this->render('contact/pro.html.twig',[
            'contacts' => $contacts
            ]
        );
    }

    #[Route('/delete/{id}', name: 'app_deletemsg')]
    public function deletemsg($id,EntityManagerInterface $entityManager): Response
    {

        $contact = $entityManager
            ->getRepository(Contact::class)
            ->find($id);

        $contact->setDeleted(1);
        $contact->setApprouved("no");
        $entityManager->persist($contact);
        $entityManager->flush();
        return $this->redirectToRoute('app_contactpro');
    }

    #[Route('/promote/{id}', name: 'app_approve')]
    public function promote($id, Request $request,EntityManagerInterface $entityManager): Response
    {
        $contact = $entityManager
            ->getRepository(Contact::class)
            ->find($id);

        $contact->setApprouved("yes");
        $contact->setDeleted(1);
        $entityManager->persist($contact);
        $entityManager->flush();
        return $this->redirectToRoute('app_contactpro');
    }

}
