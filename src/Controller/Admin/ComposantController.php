<?php

namespace App\Controller\Admin;

use App\Entity\Composant;
use App\Form\ComposantType;
use App\Repository\ComposantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/composant')]
#[IsGranted("ROLE_ADMIN")]
final class ComposantController extends AbstractController
{
    #[Route(name: 'app_composant_index', methods: ['GET'])]
    public function index(ComposantRepository $composantRepository): Response
    {
        return $this->render('admin/composant/index.html.twig', [
            'composants' => $composantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_composant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $composant = new Composant();
        $form = $this->createForm(ComposantType::class, $composant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($composant);
            $entityManager->flush();

            return $this->redirectToRoute('app_composant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/composant/new.html.twig', [
            'composant' => $composant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_composant_show', methods: ['GET'])]
    public function show(Composant $composant): Response
    {
        return $this->render('admin/composant/show.html.twig', [
            'composant' => $composant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_composant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Composant $composant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ComposantType::class, $composant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_composant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/composant/edit.html.twig', [
            'composant' => $composant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_composant_delete', methods: ['POST'])]
    public function delete(Request $request, Composant $composant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$composant->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($composant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_composant_index', [], Response::HTTP_SEE_OTHER);
    }
}
