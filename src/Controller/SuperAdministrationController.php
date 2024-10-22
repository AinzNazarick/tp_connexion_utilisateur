<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/superadministration')]
#[IsGranted("ROLE_SUPER_ADMIN")]
class SuperAdministrationController extends AbstractController
{
    #[Route('/{utilisateur}/ajout_super_admin', name: 'app_ajouter_super_admin')]
    public function index(Utilisateur $utilisateur, EntityManagerInterface $em): Response
    {
        $role_ajouter = "ROLE_SUPER_ADMIN";
        $role_supp = "ROLE_ADMIN";
        if(!in_array("ROLE_SUPER_ADMIN", $utilisateur->getRoles())) {
            $utilisateur->addRole($role_ajouter);
            if(in_array($role_supp, $utilisateur->getRoles())) {
                $utilisateur->suppRole($role_supp);
            }
            $em->persist($utilisateur);
            $em->flush();
            $this->addFlash('success', 'Ajout du rôle super admin est ok');
        } else {
            $this->addFlash('fail', 'Le rôle super admin existe déjà, pas rajouté');
        }

        return $this->redirectToRoute('app_utilisateur_index');
    }
}
