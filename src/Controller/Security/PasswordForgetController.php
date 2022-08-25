<?php

namespace App\Controller\Security;

use App\Entity\Token;
use App\Enum\Token\TypeEnum;
use App\Form\Security\PasswordForgetType;
use App\Form\Security\PasswordResetType;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Service\MailSender\MailSenderInterface;
use App\Service\MailSender\Parameter;
use App\Service\TokenGenerator\TokenGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class PasswordForgetController extends AbstractController
{
    public function __construct(
        private MailSenderInterface     $mailSender,
        private TokenGeneratorInterface $tokenGenerator,
        private TokenRepository         $tokenRepository,
        private UserRepository          $userRepository
    )
    {
    }

    #[Route(path: '/mot-de-passe-perdu', name: 'app_security_password_forget')]
    public function index(Request $request)
    {
        $form = $this->createForm(PasswordForgetType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if (null !== $user) {
                $token = $this->tokenGenerator->generate($user, TypeEnum::PASSWORD_RESET);

                $this->tokenRepository->persist($token, true);
            }

            $this->mailSender->send("Maison Jaune - Mot de passe oublié", 'security/password_forget/index', (new Parameter())
                ->addRecipient($user->getEmail())
                ->setPriority(Email::PRIORITY_HIGH)
                ->setParameters([
                    'token' => $token
                ])
            );

            $this->addFlash('success', 'Un mail à été envoyé à cette adresse afin de modifier votre mot de passe.');

            return $this->redirectToRoute('app_security_login');
        }

        return $this->render('security/password_forget/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/mot-de-passe/confirmation/{token}', name: 'app_security_password_conformation', defaults: ['title' => 'Changement de mot de passe'])]
    #[ParamConverter('token', converter: 'token', options: ['types' => [TypeEnum::PASSWORD_RESET]])]
    public function confirmation(Request $request, Token $token, string $title)
    {
        $form = $this->createForm(PasswordResetType::class, $token->getUser());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->userRepository->persist($token->getUser());
            $this->tokenRepository->remove($token, true);

            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès');

            return $this->redirectToRoute($token->getUser()->isMember() ? 'app_security_login' : 'app_home');
        }

        return $this->render('security/password_reset/index.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
        ]);
    }
}
