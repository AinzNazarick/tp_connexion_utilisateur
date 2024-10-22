<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/administration')]
#[IsGranted("ROLE_SUPER_ADMIN")]
class AdministrationController extends AbstractController
{
    #[Route('/{utilisateur}/ajout_admin', name: 'app_ajouter_role_admin')]
    public function index(Utilisateur $utilisateur, EntityManagerInterface $em): Response
    {
        $role_ajouter = "ROLE_ADMIN";
        if(!in_array("ROLE_ADMIN", $utilisateur->getRoles())) {
            $utilisateur->addRole($role_ajouter);
            $em->persist($utilisateur);
            $em->flush();
            $this->addFlash('success', 'Ajout du rôle admin est ok');
        } else {
            $this->addFlash('fail', 'Le role admin existe déjà, pas rajouté');
        }

        return $this->redirectToRoute('app_utilisateur_index');
    }
}
