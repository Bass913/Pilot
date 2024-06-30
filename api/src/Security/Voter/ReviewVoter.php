<?php

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ReviewVoter extends Voter
{
    public const CREATE = 'REVIEW_CREATE';
    public const DELETE = 'REVIEW_DELETE';

    private ?Security $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CREATE, self::DELETE])
            && $subject instanceof \App\Entity\Review;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CREATE:
                if($user === $subject->getClient()){return true;}
                break;
            case self::DELETE:
                if($this->security->isGranted("ROLE_SUPERADMIN")  ){
                    return true;
                }
                if($user === $subject->getClient()){return true;}
                break;
        }

        return false;
    }
}
