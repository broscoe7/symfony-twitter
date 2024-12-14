<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Create a new user account',
)]
class CreateUserCommand extends Command
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher,
                                private readonly EntityManagerInterface $manager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
//        $this
////            ->addArgument('password', InputArgument::REQUIRED, 'New user password')
//            ->addArgument('email', InputArgument::REQUIRED, 'New user email address');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // Using Arguments
        // $email = $input->getArgument('email');
        // $password = $input->getArgument('password');
        // Using User Input
        $email = $io->ask(question: "What is the new user's email address?", validator: function (string $email): string {
            if (empty($email)) {
                throw new \RuntimeException('Email cannot be empty');
            }
            return $email;
        });
        $password = $io->askHidden(question: "What is the new user's password?", validator: function (string $password): string {
            if (empty($password)) {
                throw new \RuntimeException('The new user\'s password cannot be empty.');
            }
            return $password;
        });
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $this->manager->persist($user);
        $this->manager->flush();

        $io->success(sprintf('User %s account successfully created!', $email));

        return Command::SUCCESS;
    }
}
