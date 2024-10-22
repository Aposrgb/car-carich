<?php

namespace App\Helper\DTO\Security;

final readonly class TelegramSecurityDTO
{
    public function __construct(
        public string $auth_date = '',
        public string $username = '',
        public string $hash = '',
        public string $id = '',
        public string $first_name = '',
        public string $photo_url = '',
    ) {
    }
}