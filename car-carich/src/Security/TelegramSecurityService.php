<?php

namespace App\Security;

use App\Helper\DTO\Security\TelegramSecurityDTO;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class TelegramSecurityService
{
    public function __construct(
        #[Autowire(env: 'TELEGRAM_TOKEN')]
        private string $token,
    ){}

    public function checkTelegramAuth(TelegramSecurityDTO $dto): bool
    {
        $auth_data = json_decode(json_encode($dto), true);
        unset($auth_data['hash']);
        ksort($auth_data);

        $dataCheck = [];
        foreach ($auth_data as $key => $value) {
            $dataCheck[] = "$key=$value";
        }

        $dataCheckString = implode("\n", $dataCheck);

        $secret_key = hash('sha256', $this->token, true);
        $hash = hash_hmac('sha256', $dataCheckString, $secret_key);

        if ($hash != $dto->hash) {
            return false;
        }

        if ((time() - (int)$dto->auth_date) > 86400) {
            return false;
        }

        return true;
    }
}