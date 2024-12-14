<?php

namespace App\Security;

use App\Entity\User;
use DateTime;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    /**
     * @param User $user
     * @return void
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if ($user->getBannedUntil() === null) {
            return;
        }
        $now = new DateTime();
        if ($now < $user->getBannedUntil()) {
            throw new AccessDeniedException("User has been blocked from this site.");
        }
    }

    /**
     * @param User $user
     * @return void
     */
    public function checkPostAuth(UserInterface $user): void
    {
        return;
    }
}