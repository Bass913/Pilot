<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UnavailabilityVoter extends Voter
{
    public const EDIT = 'UNAVAILABILITY_EDIT';
    public const CREATE = 'UNAVAILABILITY_CREATE';
    public const DELETE = 'UNAVAILABILITY_DELETE';
    public const READ = 'UNAVAILABILITY_READ';
    private ?Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::CREATE, self::DELETE, self::READ])
            && $subject instanceof \App\Entity\Unavailability;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }
        //var_dump($subject);
        //die();

        $companies = [];
        if($this->security->isGranted("ROLE_ADMIN")){
            $companies = $user->getCompanies()->toArray();
        }


        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CREATE:
            case self::DELETE:
            case self::EDIT:
            case self::READ:
                if($this->security->isGranted("ROLE_SUPERADMIN")  ){
                    return true;
                }
                if($this->security->isGranted("ROLE_ADMIN") && ( ($subject->getUser() !== null && in_array($subject->getUser()->getCompany(), $companies)) or ($subject->getCompany() !== null && (in_array($subject->getCompany(), $companies)))) ){
                    return true;
                }
                if(!$this->security->isGranted("ROLE_ADMIN") && ($user === $subject->getUser())){return true;}
                break;

        }

        return false;
    }
}
