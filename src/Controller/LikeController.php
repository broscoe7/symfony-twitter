<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LikeController extends AbstractController
{
    #[Route('/like/{id<\d+>}', name: 'app_like')]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function like(MicroPost $post, EntityManagerInterface $manager, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $post->addLikedBy($user);
        $manager->persist($post);
        $manager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/unlike/{id<\d+>}', name: 'app_unlike')]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function unlike(MicroPost $post, EntityManagerInterface $manager, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $post->removeLikedBy($user);
        $manager->persist($post);
        $manager->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
