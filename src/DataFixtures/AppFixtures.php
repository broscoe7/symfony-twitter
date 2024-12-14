<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('test1@test.com');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'password'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('test2@test.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'password'));
        $manager->persist($user2);

        $microPost1 = new MicroPost();
        $microPost1->setTitle("Welcome to Poland!");
        $microPost1->setText("Here are some places to visit.");
        $microPost1->setCreated(new \DateTime());
        $microPost1->setAuthor($user1);
        $manager->persist($microPost1);

        $microPost2 = new MicroPost();
        $microPost2->setTitle("Welcome to Canada!");
        $microPost2->setText("Here are some places to visit.");
        $microPost2->setCreated(new \DateTime());
        $microPost2->setAuthor($user2);
        $manager->persist($microPost2);

        $microPost3 = new MicroPost();
        $microPost3->setTitle("Welcome to Iceland!");
        $microPost3->setText("Here are some places to visit.");
        $microPost3->setCreated(new \DateTime());
        $microPost3->setAuthor($user1);
        $manager->persist($microPost3);

        $manager->flush();
    }
}
