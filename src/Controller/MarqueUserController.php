<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Repository\MarqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/marque')]
#[IsGranted("ROLE_USER")]
class MarqueUserController extends AbstractController
{
    #[Route(name: 'app_marque_user_index', methods: ['GET'])]
    public function index(MarqueRepository $marqueRepository): Response
    {
        $marques = $marqueRepository->findNoArchived();
        return $this->render('marque_user/user_index.html.twig', [
            'marques' => $marques,
        ]);
    }

    #[Route('/show/{id}', name: 'app_marque_user_show', methods: ['GET'])]
    public function show(Marque $marque): Response
    {
        return $this->render('marque_user/user_show.html.twig', [
            'marque' => $marque,
            'voitures' => $marque->getVoitures(),
        ]);
    }
}
