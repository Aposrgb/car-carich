<?php

namespace App\Controller\Admin;

use App\Entity\Settings;
use App\Helper\Enum\Type\SettingsType as EnumSettingsType;
use App\Form\SettingsType;
use App\Helper\DTO\Settings\SettingsAdminResponse;
use App\Helper\Mapper\Mapper;
use App\Repository\SettingsRepository;
use App\Service\FileUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/settings')]
class SettingsController extends AbstractController
{
    #[Route('/', name: 'app_settings_index', methods: ['GET'])]
    public function index(SettingsRepository $settingsRepository, Mapper $mapper): Response
    {
        return $this->render('admin/settings/index.html.twig', [
            'settings' => $mapper->entitiesToResponseDTO($settingsRepository->findAll(), SettingsAdminResponse::class),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_settings_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Settings $setting, EntityManagerInterface $entityManager, FileUploadService $fileUploadService): Response
    {
        $form = $this->createForm(SettingsType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $request->files->all()['settings'] ?? [];
            if ($setting->getType() == EnumSettingsType::CONTRACT->value and array_key_exists('img', $files) and !empty($files['img'])) {
                if (!empty($setting->getImg())) {
                    $fileUploadService->deleteFile($setting->getImg());
                }
                $setting->setImg(
                    $fileUploadService->upload($files['img'], FileUploadService::CONTRACT_PATH)
                );
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_settings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/settings/edit.html.twig', [
            'setting' => new SettingsAdminResponse($setting),
            'form' => $form,
        ]);
    }
}
