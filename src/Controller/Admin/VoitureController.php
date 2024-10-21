<?php

namespace App\Controller\Admin;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/voiture')]
#[IsGranted("ROLE_ADMIN")]
final class VoitureController extends AbstractController
{
    #[Route(name: 'app_voiture_index', methods: ['GET'])]
    public function index(VoitureRepository $voitureRepository): Response
    {
        return $this->render('voiture/index.html.twig', [
//            'voitures' => $voitureRepository->findAll(),
            'voitures' => $voitureRepository->findNoArchived(),
        ]);
    }

    #[Route('/new', name: 'app_voiture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($voiture);
            $entityManager->flush();

            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voiture/new.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_voiture_show', methods: ['GET'])]
    public function show(Voiture $voiture): Response
    {
        return $this->render('voiture/show.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_voiture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voiture $voiture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voiture/edit.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_voiture_delete', methods: ['POST'])]
    public function delete(Request $request, Voiture $voiture, EntityManagerInterface $entityManager): Response
    {
        if (!$voiture->isArchive()) {
            $this->addFlash('danger', 'Impossible de supprimer une voiture non archivée');
            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete' . $voiture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($voiture);
            $entityManager->flush();
            $this->addFlash('success', 'Voiture supprimée avec succès');
        }

        return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/archive', name: 'app_voiture_archive_list', methods: ['GET'])]
    public function archiveList(VoitureRepository $voitureRepository): Response
    {
        $voituresArchivees = $voitureRepository->findArchivedVoitures();

        return $this->render('voiture/archive_list.html.twig', [
            'voitures' => $voituresArchivees,
        ]);
    }

    #[Route('/archive/{id}', name: 'app_voiture_archive', methods: ['GET'])]
    public function archive(Voiture $voiture, EntityManagerInterface $entityManager): Response
    {
//        $voiture->setArchive(true);
//        $entityManager->flush();

//        $this->addFlash('success', 'La voiture a été archivée avec succès');
//        return $this->redirectToRoute('app_voiture_index');

        return $this->redirectToRoute('app_voiture_archive_confirm', [
            'id' => $voiture->getId(),
        ]);
    }

    #[Route('/archive/confirm/{id}', name: 'app_voiture_archive_confirm', methods: ['GET', 'POST'])]
    public function confirmArchive(Request $request, Voiture $voiture, EntityManagerInterface $entityManager): Response
    {
        if($request->isMethod('POST')) {
            if($this->isCsrfTokenValid('archive'.$voiture->getId(), $request->request->get('_token'))){
                $voiture->setArchive(true);
                $entityManager->flush();

                $this->addFlash('success', 'La voiture a été archivée avec succès.');
                return $this->redirectToRoute('app_voiture_index');
            } else {
                $this->addFlash('danger', 'CSRF invalide');
            }
        }

        return $this->render('voiture/archive_confirm.html.twig', [
            'voiture' => $voiture,
        ]);
    }
}
