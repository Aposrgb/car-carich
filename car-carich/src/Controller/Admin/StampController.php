<?php

namespace App\Controller\Admin;

use App\Entity\Stamp;
use App\Form\StampType;
use App\Repository\StampRepository;
use App\Service\FileUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stamp')]
class StampController extends AbstractController
{
    #[Route('/', name: 'app_stamp_index', methods: ['GET'])]
    public function index(StampRepository $stampRepository): Response
    {
        return $this->render('admin/stamp/index.html.twig', [
            'stamps' => $stampRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stamp_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploadService $fileUploadService): Response
    {
        $stamp = new Stamp();
        $form = $this->createForm(StampType::class, $stamp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allFiles = $request->files->all()['stamp'] ?? [];
            if (array_key_exists('icon', $allFiles) and !empty($allFiles['icon'])) {
                $stamp->setIcon(
                    $fileUploadService->upload($allFiles['icon'], FileUploadService::STAMP_ICON_PATH)
                );
            }
            $entityManager->persist($stamp);
            $entityManager->flush();

            return $this->redirectToRoute('app_stamp_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/stamp/new.html.twig', [
            'stamp' => $stamp,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stamp_show', methods: ['GET'])]
    public function show(Stamp $stamp): Response
    {
        return $this->render('admin/stamp/show.html.twig', [
            'stamp' => $stamp,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stamp_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stamp $stamp, EntityManagerInterface $entityManager, FileUploadService $fileUploadService): Response
    {
        $form = $this->createForm(StampType::class, $stamp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allFiles = $request->files->all()['stamp'] ?? [];
            if (array_key_exists('icon', $allFiles) and !empty($allFiles['icon'])) {
                if ($stamp->getIcon()) {
                    $fileUploadService->deleteFile($stamp->getIcon());
                }
                $stamp->setIcon(
                    $fileUploadService->upload($allFiles['icon'], FileUploadService::STAMP_ICON_PATH)
                );
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_stamp_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/stamp/edit.html.twig', [
            'stamp' => $stamp,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stamp_delete', methods: ['POST'])]
    public function delete(Request $request, Stamp $stamp, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $stamp->getId(), $request->request->get('_token'))) {
            $entityManager->remove($stamp);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stamp_index', [], Response::HTTP_SEE_OTHER);
    }
}
