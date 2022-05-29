<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Facture;
use App\Form\OpenPdfType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        return $this->renderForm('profile/profile.html.twig', [
            'controller_name' => 'ProfileController',
            'factures' => $factures
        ]);
    }

}
