<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_index', methods: ['GET'])]
    public function index(UtilisateurRepository $userRepo): Response
    {
        if($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->render('admin/index.html.twig', [
                'users' => $userRepo->findAll(),
            ]);
        }
        return $this->redirectToRoute('home.index');
    }
}
