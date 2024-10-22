<?php

namespace App\Controller;

use App\Security\VkAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\{DependencyInjection\Attribute\Autowire,
    HttpFoundation\RedirectResponse,
    HttpFoundation\Request,
    HttpFoundation\Response,
    Routing\Attribute\Route
};

#[Route('/security')]
class SecurityController extends AbstractController
{
    public const PATH = 'app_lk_profile';

    public function __construct(
        #[Autowire(env: 'VK_ID')]
        private string $vkClientId,
        #[Autowire(env: 'VK_METHOD_REDIRECT')]
        private string $vkRedirect,
        #[Autowire(env: 'VK_AUTH_REDIRECT')]
        private string $vkAuthRedirect,
    ) {
    }

    #[Route('/login', name: 'app_lk_login', methods: ['GET'])]
    public function login(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute(self::PATH);
        }
        return $this->render('lk/security/sign.html.twig');
    }

    #[Route('/register', name: 'app_lk_register', methods: ['GET'])]
    public function register(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute(self::PATH);
        }
        return $this->render('lk/security/register.html.twig');
    }

    #[Route('/logout', name: 'app_lk_logout')]
    public function logoutUser(): Response
    {
        return $this->json([]);
    }

    #[Route('/login/dev', name: 'app_lk_dev_login', methods: ['GET'], env: 'dev')]
    public function loginDev(): Response
    {
        throw new \Exception('Этот метод обрабатывается через TelegramAuthenticator');
    }

    /**
     * @throws \Exception
     */
    #[Route('/login/telegram', name: 'login_telegram', methods: ['GET'])]
    public function loginTelegram(): Response
    {
        throw new \Exception('Этот метод обрабатывается через TelegramAuthenticator');
    }

    /**
     * @throws \Exception
     */
    #[Route('/login/vk', name: 'login_vk', methods: ['GET'])]
    public function redirectToVk(Request $request): RedirectResponse
    {
        $state = bin2hex(random_bytes(16));

        $session = $request->getSession();
        $session->set(VkAuthenticator::SESSION_STATE_KEY, $state);

        $vkAuthUrl = sprintf(
            $this->vkAuthRedirect,
            $this->vkClientId,
            $this->vkRedirect,
            $state
        );

        return new RedirectResponse($vkAuthUrl);
    }

    /**
     * @throws \Exception
     */
    #[Route('/login/callback-vk', methods: ['GET'])]
    public function callbackVk(): Response
    {
        throw new \Exception('Этот метод обрабатывается через VkAuthenticator');
    }
}