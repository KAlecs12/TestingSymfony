<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\OpenPdfType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, KernelInterface $kernelInterface): Response
    {
        $facturation = new Facture();
        $form = $this->createForm(OpenPdfType::class, $facturation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $facture = $form->get('titre')->getData();

            $facturation = $facture;

            $content = $facturation->getContent();

            $projectRoot = $kernelInterface->getProjectDir();
            return $this->file( $projectRoot.$content, null, ResponseHeaderBag::DISPOSITION_INLINE);
        }

        return $this->renderForm('profile/profile.html.twig', [
            'controller_name' => 'ProfileController',
            'form' => $form,
        ]);
    }
//    #[Route('/pdf/', name: 'download_file')]
//    public function pdfAction(KernelInterface $kernelInterface): Response
//    {
//
//
//        $projectRoot = $kernelInterface->getProjectDir();
//        return $this->file( $projectRoot.$content, null, ResponseHeaderBag::DISPOSITION_INLINE);
//
//    }
}
