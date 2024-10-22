<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/superadmin')]
#[IsGranted("ROLE_SUPER_ADMIN")]
class SuperAdminController extends AbstractController
{
    #[Route('', name: 'app_superadmin_index', methods: ['GET'])]
    public function index(UtilisateurRepository $userRepo): Response
    {
        if($this->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->render('superadmin/user/index.html.twig', [
                'users' => $userRepo->findAll(),
            ]);
        }
        return $this->redirectToRoute('home.index');
    }

    #[Route('/role/{id}', name: 'app_superadmin_change_role', methods: ['GET'])]
    public function changeRoleUser(Utilisateur $user, EntityManagerInterface $em): Response{
        if($this->isGranted('ROLE_SUPER_ADMIN')) {
            $user->setRoles(['ROLE_ADMIN']);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_superadmin_index');
        }
        return $this->redirectToRoute('home.index');
    }
}
