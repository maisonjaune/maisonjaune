<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class MainAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const ROUTE_LOGIN = 'app_security_login';
    public const ROUTE_HOME = 'app_home';
    public const ROUTE_ADMIN = 'app_admin_dashboard';

    public const FORM_EMAIL = 'email';
    public const FORM_PASSWORD = 'password';
    public const FORM_TOKEN = '_token';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get(self::FORM_EMAIL, '');
        $password = $request->request->get(self::FORM_PASSWORD, '');
        $csrfToken = $request->request->get(self::FORM_TOKEN);

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(new UserBadge($email), new PasswordCredentials($password), [
            new CsrfTokenBadge('authenticate', $csrfToken),
        ]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        $user = $token->getUser();

        return $user instanceof User && $user->isMember()
            ? new RedirectResponse($this->urlGenerator->generate(self::ROUTE_ADMIN))
            : new RedirectResponse($this->urlGenerator->generate(self::ROUTE_HOME));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::ROUTE_LOGIN);
    }
}
