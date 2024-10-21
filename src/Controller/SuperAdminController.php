<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SuperAdminController extends AbstractController
{
    #[Route('/superadmin', name: 'app_superadmin_index', methods: ['GET'])]
    public function index(UtilisateurRepository $userRepo): Response
    {
        if($this->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->render('superadmin/user/index.html.twig', [
                'users' => $userRepo->findAll(),
            ]);
        }
        return $this->redirectToRoute('home.index');
    }

    #[Route('/superadmin/role/{id}', name: 'app_superadmin_change_role', methods: ['GET'])]
    public function changeRoleUser(Utilisateur $user, EntityManagerInterface $em): Response{
        if($this->isGranted('ROLE_SUPER_ADMIN')) {
            $user->setRoles(['ROLE_ADMIN']);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_super-admin_index');
        }
        return $this->redirectToRoute('home.index');
    }
}
