<?php

namespace App\Security;

use App\Helper\Exception\ApiException;
use App\Service\UserService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\{Request, RedirectResponse, Response};
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\{AbstractAuthenticator,
    Passport\Badge\UserBadge,
    Passport\Passport,
    Passport\SelfValidatingPassport
};

class VkAuthenticator extends AbstractAuthenticator
{
    public const SESSION_STATE_KEY = 'oauth2state';

    public function __construct(
        #[Autowire(env: 'VK_ID')]
        private readonly string $clientId,
        #[Autowire(env: 'VK_SECRET')]
        private readonly string $clientSecret,
        #[Autowire(env: 'VK_METHOD_REDIRECT')]
        private readonly string $vkRedirect,
        #[Autowire(env: 'VK_GET_ACCESS')]
        private readonly string $vkGetAccess,
        private readonly RouterInterface $router,
        private readonly UserService $userService,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() === '/security/login/callback-vk' && $request->query->has(
                'code'
            ) && $request->query->has('state');
    }

    public function authenticate(Request $request): Passport
    {
        $code = $request->query->get('code');
        $state = $request->query->get('state');

        $sessionState = $request->getSession()->get(self::SESSION_STATE_KEY);

        if (!$state || $state !== $sessionState) {
            throw new AuthenticationException('Неверый параметр состояния');
        }

        $request->getSession()->remove(self::SESSION_STATE_KEY);

        $client = new Client();
        try {
            $response = $client->post($this->vkGetAccess, [
                RequestOptions::FORM_PARAMS => [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'redirect_uri' => $this->vkRedirect,
                    'code' => $code,
                ],
            ]);
        } catch (\Exception|GuzzleException) {
            throw new AuthenticationException('Invalid state parameter.');
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (!array_key_exists('user_id', $data) || !array_key_exists('access_token', $data)) {
            throw new AuthenticationException('Не получилось авторизоваться через Vk');
        }

        $userId = $data['user_id'];
        $accessToken = $data['access_token'];

        $user = $this->userService->createOrUpdateUserByVk($userId, $accessToken);
        if (!$user) {
            throw new AuthenticationException('Не найдена информация о пользователе');
        }

        return new SelfValidatingPassport(new UserBadge($user->getId()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->router->generate('index'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw new ApiException('Не удалось авторизоваться через Vk, попробуйте позднее');
    }
}
