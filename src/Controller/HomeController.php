<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {

        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);

    }

    #[Route('/pdf/', name: 'download_file')]
    public function pdfAction(KernelInterface $kernelInterface)
    {
        $facturation = $this->getUser()->getFacturation();

        $projectRoot = $kernelInterface->getProjectDir();
        return $this->file( $projectRoot.$facturation, null, ResponseHeaderBag::DISPOSITION_INLINE);

    }
}
