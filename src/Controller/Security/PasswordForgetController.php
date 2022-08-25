<?php

namespace App\Controller\Security;

use App\Entity\Token;
use App\Enum\Token\TypeEnum;
use App\Form\Security\PasswordForgetType;
use App\Repository\TokenRepository;
use App\Service\MailSender\MailSenderInterface;
use App\Service\MailSender\Parameter;
use App\Service\TokenGenerator\TokenGeneratorInterface;
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
    #[Route(path: '/inscription/confirmation/mot-de-passe/{token}', name: 'security_inscription_conformation', defaults: ['title' => 'Confirmation d\'inscription'])]
    public function confirmation(Request $request, Token $token, string $title)
    {
        dd($token);
    }
}
