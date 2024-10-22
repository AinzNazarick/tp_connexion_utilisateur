<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Repository\VoitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/voiture')]
#[isGranted("ROLE_USER")]
class VoitureUserController extends AbstractController
{
    #[Route(name: 'app_voiture_user_index', methods: ['GET'])]
    public function index(VoitureRepository $voitureRepository): Response
    {
        return $this->render('voiture_user/user_index.html.twig', [
            'voitures' => $voitureRepository->findNoArchived(),
        ]);
    }

    #[Route('/show/{id}', name: 'app_voiture_user_show', methods: ['GET'])]
    public function show(Voiture $voiture): Response
    {
        return $this->render('voiture_user/user_show.html.twig', [
            'voiture' => $voiture,
        ]);
    }
}
