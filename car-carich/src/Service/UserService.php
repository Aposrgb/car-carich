<?php

namespace App\Service;

use App\Entity\User;
use App\Helper\DTO\Security\TelegramSecurityDTO;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class UserService
{

    public function __construct(
        private UserRepository $userRepository,

        #[Autowire(env: "VK_GET_USER_INFO")]
        private readonly string $vkGetUserInfo,
    ) {
    }

    public function createOrUpdateUserByVk(int $userId, string $accessToken): ?User
    {
        $user = $this->userRepository->findOneBy([
            'vkId' => $userId
        ]);

        if ($user) {
            return $user;
        }

        $vkApiUrl = sprintf($this->vkGetUserInfo, $accessToken);

        $response = file_get_contents($vkApiUrl);
        if (!$response) {
            return null;
        }

        $data = json_decode($response, true);

        if (!isset($data['response'][0])) {
            return null;
        }

        $user = $data['response'][0];
        $firstName = $user['first_name'];
        $lastName = $user['last_name'];
        $photo = $user['photo_200'];

        $user = new User();

        $user
            ->setName($firstName . ' ' . $lastName)
            ->setVkId($userId)
            //            ->setPhoto()
        ;

        $this->userRepository->add($user, true);
        return $user;
    }

    public function createOrUpdateUserByTelegram(TelegramSecurityDTO $telegramSecurityDTO): User
    {
        $user = $this->userRepository->findOneBy([
            'telegramId' => $telegramSecurityDTO->id,
        ]);

        if ($user) {
            return $user;
        }

        $user = new User();
        $user
//            ->setPhoto()
            ->setName($telegramSecurityDTO->first_name)
            ->setTelegramId($telegramSecurityDTO->id)
            ->setTelegramUsername($telegramSecurityDTO->username);

        $this->userRepository->add($user, true);
        return $user;
    }
}