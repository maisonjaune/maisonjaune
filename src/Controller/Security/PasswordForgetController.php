<?php

namespace App\Controller\Security;

use App\Enum\Token\TypeEnum;
use App\Form\Security\PasswordForgetType;
use App\Repository\TokenRepository;
use App\Service\TokenGenerator\TokenGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PasswordForgetController extends AbstractController
{
    public function __construct(
        private TokenGeneratorInterface $tokenGenerator,
        private TokenRepository $tokenRepository,
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

            $this->addFlash('success', 'Un mail à été envoyé à cette adresse afin de modifier votre mot de passe.');

            return $this->redirectToRoute('app_security_login');
        }

        return $this->render('security/password_forget/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
