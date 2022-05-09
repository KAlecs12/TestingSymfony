<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddFileFormType;
use App\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/')]
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function new(Request $request, SluggerInterface $slugger, FileUploader $fileUploader): Response
    {
        $user = new User();
        $form = $this->createForm(AddFileFormType::class, $user);
        $form->handleRequest($request);
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $uploadedFile */
                $uploadedFile = $form->get('imageFilename')->getData();
                if ($uploadedFile) {
                    $uploadedFileName = $fileUploader->upload($uploadedFile);
                    $user->setImageFilename($uploadedFileName);
                }

                return $this->redirectToRoute('app_admin');
            }
        return $this->renderForm('admin/admin.html.twig', [
            'form' => $form,
        ]);
    }

}
