<?php

namespace App\Controller\Admin;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/marque')]
#[IsGranted("ROLE_ADMIN")]
final class MarqueController extends AbstractController
{
    #[Route(name: 'app_marque_index', methods: ['GET'])]
    public function index(MarqueRepository $marqueRepository): Response
    {
        $marques = $marqueRepository->findNoArchived();
        return $this->render('admin/marque/index.html.twig', [
//            'marques' => $marqueRepository->findAll(),
            'marques' => $marques,
        ]);
    }

    #[Route('/new', name: 'app_marque_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($marque);
            $entityManager->flush();

            return $this->redirectToRoute('app_marque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/marque/new.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_marque_show', methods: ['GET'])]
    public function show(Marque $marque): Response
    {
        return $this->render('admin/marque/show.html.twig', [
            'marque' => $marque,
            'voitures' => $marque->getVoitures(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_marque_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Marque $marque, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_marque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/marque/edit.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_marque_delete', methods: ['POST'])]
    public function delete(Request $request, Marque $marque, EntityManagerInterface $entityManager): Response
    {
        if ($marque->isArchive() || $marque->getVoitures()->isEmpty()) {
            if ($this->isCsrfTokenValid('delete'.$marque->getId(), $request->request->get('_token'))) {

                foreach ($marque->getVoitures() as $voiture) {
                    $entityManager->remove($voiture);
                }

                $entityManager->remove($marque);
                $entityManager->flush();
                $this->addFlash('success', 'Marque et voiture associées supprimées avec succès.');
            }
        } else {
            $this->addFlash('danger', 'Impossible de supprimer une marque avec des voitures non archivées.');
        }

        return $this->redirectToRoute('app_marque_index');
    }

    #[Route('/archive/confirme/{id}', name: 'app_marque_archive_confirm', methods: ['GET','POST'])]
    public function confirmArchive(Request $request, Marque $marque, EntityManagerInterface $entityManager): Response
    {
        if($request->isMethod('POST')) {
            if($this->isCsrfTokenValid('archive'.$marque->getId(), $request->request->get('_token'))) {
                $marque->archiveMarque();
                $entityManager->flush();
                $this->addFlash('success', 'La marque et les voitures associées sont archivées avec succès.');

                return $this->redirectToRoute('app_marque_index');
            }
        }

        return $this->render('admin/marque/confirm_archive.html.twig', [
            'marque' => $marque,
        ]);

    }

    #[Route('/archive', name: 'app_marque_archive_list', methods: ['GET'])]
    public function archiveList(MarqueRepository $marqueRepository): Response
    {
        $marquesArchivees = $marqueRepository->findArchivedMarque();

        return $this->render('admin/marque/archive_list.html.twig', [
            'marques' => $marquesArchivees,
        ]);
    }

    #[Route('/archive/{id}', name: 'app_marque_archive', methods: ['GET'])]
    public function archive(EntityManagerInterface $entityManager, Marque $marque): Response
    {

        return $this->redirectToRoute('app_marque_archive_confirm', [
            'id' => $marque->getId(),
        ]);

//        $marque = $entityManager->getRepository(Marque::class)->find($marque->getId());
//        if(!$marque){
//            throw $this->createNotFoundException('Marque non trouvée');
//        }
//
//        $marque->archiveMarque();
//
//        $entityManager->flush();
//
//        $this->addFlash('success', 'La marque et les voitures associées sont archivées avec succès');
//        return $this->redirectToRoute('app_marque_index', [
//            'id' => $marque->getId(),
//        ] );
    }
}
