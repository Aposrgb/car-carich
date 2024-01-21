<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TelegramService
{
    public function __construct(
        protected ParameterBagInterface $parameterBag,
    )
    {
    }

    public function sendMessage(string $message): bool
    {
        if(empty($chatId = $this->getChatId()) or empty($token = $this->getToken())) {
            return false;
        }
        $response = file_get_contents(
            $this->parameterBag->get('TELEGRAM_API') .
            $token .
            $this->parameterBag->get('TELEGRAM_SEND_MESSAGE') .
            http_build_query([
                'chat_id' => $chatId,
                'text' => $message
            ])
        );
        return (bool)$response;
    }

    private function getChatId(): string
    {
        return $this->parameterBag->get('TELEGRAM_CHAT_ID');
    }

    private function getToken(): string
    {
        return $this->parameterBag->get('TELEGRAM_TOKEN');
    }
}