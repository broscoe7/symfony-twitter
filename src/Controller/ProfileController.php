<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;

class ProfileController extends AbstractController
{
  #[Route('/profile/{id<\d+>}', name: 'app_profile')]
  public function index(User $user): Response
  {
    return $this->render('profile/show.html.twig', [
      "user" => $user,
    ]);
  }
}
