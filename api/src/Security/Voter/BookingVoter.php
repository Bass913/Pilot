<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BookingVoter extends Voter
{
    public const EDIT = 'BOOKING_EDIT';
    public const CREATE = 'BOOKING_CREATE';
    public const DELETE = 'BOOKING_DELETE';
    public const READ = 'BOOKING_READ';
    public const READ_BOOKING_COMAPNY = 'BOOKING_READ_COMPANY';
    private ?Security $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::CREATE, self::DELETE, self::READ, self::READ_BOOKING_COMAPNY])
            && $subject instanceof \App\Entity\Booking;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        $companies = [];
        if($this->security->isGranted("ROLE_ADMIN")){
            $companies = $user->getCompanies()->toArray();
        }


        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::READ:
            case self::CREATE:
            case self::DELETE:
            case self::EDIT:
                if($this->security->isGranted("ROLE_SUPERADMIN")  ){
                    return true;
                }
                if($this->security->isGranted("ROLE_ADMIN") && in_array($subject->getCompany(), $companies) ){
                    return true;
                }
                if(!$this->security->isGranted("ROLE_ADMIN") && ($user === $subject->getClient())){return true;}
                break;

        }

        return false;
    }
}
