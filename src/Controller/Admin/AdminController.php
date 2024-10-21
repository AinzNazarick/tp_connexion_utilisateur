<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin_index', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function index(UtilisateurRepository $userRepo): Response
    {
        if($this->isGranted('ROLE_ADMIN')) {

            $users = $userRepo->findAll();
            $this->addFlash('success', 'Bonjour, '. $this->getUser()->getEmail());
            return $this->render('admin/index.html.twig', [
                'users' => $users,
            ]);
        }

        return $this->redirectToRoute('app_login');
    }
}
