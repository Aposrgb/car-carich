<?php

namespace App\Controller\Api;

use App\Service\TelegramService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TelegramController extends AbstractController
{
    public function __construct(
        protected TelegramService $telegramService
    )
    {
    }

    #[Route('/send-message', name: 'app_send_tg_message', methods: ['POST'])]
    public function sendMessage(Request $request): Response
    {
        $message = json_decode($request->getContent(), true)['message'] ?? null;
        if ($message and !empty($message)) {
            $this->telegramService->sendMessage($message);
        }
        return $this->json([]);
    }
}