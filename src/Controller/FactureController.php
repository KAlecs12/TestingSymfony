<?php

namespace App\Controller;

use App\Entity\Facture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends AbstractController
{
    // affichage de facture byId en fonction du User
    #[Route('/facture/{id}', name: 'app_facture')]
    public function show($id, EntityManagerInterface $entityManager, KernelInterface $kernelInterface): Response
    {
        $factures = $entityManager
            ->getRepository(Facture::class)
            ->find($id);

        $content = $factures->getContent();

        $projectRoot = $kernelInterface->getProjectDir();
        return $this->file( $projectRoot.$content, null, ResponseHeaderBag::DISPOSITION_INLINE);

    }
}
