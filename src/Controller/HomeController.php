<?php

namespace App\Controller;

use App\Entity\Node\Post;
use App\Service\Provider\Post\PostProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_home')]
class HomeController extends AbstractController
{
    public function __invoke(PostProviderInterface $postProvider): Response
    {
        /** @var Post $post */
        $post = $postProvider->findLastSticky()[0];

        return $this->render('home/index.html.twig', [
            'mainPosts' => $postProvider->findLastSticky(),
        ]);
    }
}
