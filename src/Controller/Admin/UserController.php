<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\Filter\FilterType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\Admin\EntityFilter;
use App\Service\Admin\EntityProvider;
use App\Service\Admin\EntityProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    private EntityProviderInterface $entityProvider;

    public function __construct(UserRepository $userRepository)
    {
        $this->entityProvider = new EntityProvider($userRepository);
    }

    #[Route('/', name: 'app_admin_user_index', methods: [Request::METHOD_GET])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(FilterType::class);

        $form->handleRequest($request);

        $entities = $this->entityProvider->getList($request, $form->getData());

        return $this->render('admin/user/index.html.twig', [
            'entities' => $entities,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_admin_user_new', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show', methods: [Request::METHOD_GET])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
