<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\MicroPostRepository;
use App\Repository\UserProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HelloController extends AbstractController
{
    private array $messages = [
        ['message' => 'Hello!', 'created' => '2024/09/12'],
        ['message' => 'Hi!', 'created' => '2024/08/12'],
        ['message' => 'Bye!', 'created' => '2021/05/12']
    ];

    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $manager, MicroPostRepository $postRepository): Response
    {
//        $user = new User();
//        $user->setEmail("test@test.com");
//        $user->setPassword("password");
//        $profile = new UserProfile();
//        $profile->setName("Someone");
//        $profile->setUser($user);
//        $manager->persist($profile);
//        $manager->flush();

//        $post = $postRepository->find(4);
//        dd($post);
//        $post->setTitle("Hello");
//        $post->setText("Hi there.");
//        $post->setCreated(new \DateTime());
//        $comment = new Comment();
//        $comment->setText("A comment");
//        $post->addComment($comment);
//        // $comment->setPost($post);
//        $manager->persist($post);
//        $manager->flush();


        return $this->render("hello/index.html.twig", ["messages" => $this->messages, "limit" => 3]);
    }

    #[Route('/messages/{id<\d+>}', name: 'app_show')]
    public function show($id): Response
    {
        return $this->render('hello/show.html.twig', ['message' => $this->messages[$id]]);
    }
}
