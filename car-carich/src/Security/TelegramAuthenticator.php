<?php

namespace App\Security;

use App\Helper\DTO\Security\TelegramSecurityDTO;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\{RedirectResponse, Request, Response};
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\{AbstractAuthenticator,
    Passport\Badge\UserBadge,
    Passport\Passport,
    Passport\SelfValidatingPassport
};

final class TelegramAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly UserService $userService,
        private readonly TelegramSecurityService $securityService,
        private readonly DenormalizerInterface $denormalizer,
        private readonly RouterInterface $router,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() === '/security/login/telegram' && $request->isMethod('GET');
    }

    public function authenticate(Request $request): Passport
    {
        $req = $request->query->all();

        /** @var TelegramSecurityDTO $telegramDTO */
        $telegramDTO = $this->denormalizer->denormalize($req, TelegramSecurityDTO::class);

        if (!$this->securityService->checkTelegramAuth($telegramDTO)) {
            throw new CustomUserMessageAuthenticationException('Ошибка проверки данных Telegram.');
        }

        $user = $this->userService->createOrUpdateUserByTelegram($telegramDTO);

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->router->generate('index'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw new CustomUserMessageAuthenticationException('Ошибка аутентификации через Telegram.');
    }


}
