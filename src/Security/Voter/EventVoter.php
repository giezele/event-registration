<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Event;
use App\Exception\NoSpotsAvailableException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EventVoter extends Voter
{
    public const REGISTER = 'register';

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::REGISTER && $subject instanceof Event;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if (!$subject instanceof Event) {
            return false;
        }

        if ($attribute === self::REGISTER) {
            if ($subject->getAvailableSpots() <= 0) {
                throw new NoSpotsAvailableException();
            }
            return true;
        }

        return false;
    }
}
