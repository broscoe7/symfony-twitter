<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use App\Security\Voter\MicroPostVoter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MicroPostController extends AbstractController
{
    #[Route('/micro-posts', name: 'app_micro_post')]
    public function index(MicroPostRepository $repository): Response
    {
        $posts = $repository->getAllWithComments();
        return $this->render('micro_post/index.html.twig', [
            'controller_name' => 'MicroPostController',
            'posts' => $posts,
        ]);
    }

    #[Route('/micro-posts/{id<\d+>}', name: 'app_micro_post_show')]
    public function show(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', ["post" => $post]);
    }

    #[Route("/micro-posts/add", name: "app_micro_post_add")]
    #[isGranted('ROLE_VERIFIED')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $post = new MicroPost();
        $form = $this->createForm(MicroPostType::class, new MicroPost());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setAuthor($this->getUser());
            $manager->persist($post);
            $manager->flush();
            $this->addFlash("success", "Your post has been created");
            return $this->redirectToRoute("app_micro_post");
        }
        return $this->render('micro_post/add.html.twig', ["form" => $form]);
    }

    #[Route("/micro-posts/{id<\d+>}/edit", name: "app_micro_post_edit")]
    #[isGranted(MicroPostVoter::EDIT, 'post')]
    public function edit(MicroPost $post, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(MicroPostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $manager->persist($post);
            $manager->flush();
            $this->addFlash("success", "Your post has been updated");
            return $this->redirectToRoute("app_micro_post");
        }
        return $this->render('micro_post/edit.html.twig', ["form" => $form, "post" => $post]);
    }

    #[Route("/micro-posts/{id<\d+>}/comment", name: "app_micro_post_comment")]
    #[isGranted("IS_AUTHENTICATED_FULLY")]
    public function addComment(MicroPost $post, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(CommentType::class, new Comment());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash("success", "Your comment has been successfully created.");
            return $this->redirectToRoute("app_micro_post_show", ["id" => $post->getId()]);
        }
        return $this->render('micro_post/comment.html.twig', ["form" => $form, "post" => $post]);
    }
}
