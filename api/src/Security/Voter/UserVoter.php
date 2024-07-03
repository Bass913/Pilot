<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    public const EDIT = 'USER_EDIT';
    public const EDIT_PASSWORD = 'USER_EDIT_PASSWORD';

    public const CREATE_EMPLOYEE = 'USER_CREATE_EMPLOYEE';

    public const DELETE = 'USER_DELETE';


    private ?Security $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::CREATE_EMPLOYEE, self::DELETE, self::EDIT_PASSWORD])
            && $subject instanceof \App\Entity\User;
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
            case self::EDIT:
            case self::DELETE:
                if($this->security->isGranted("ROLE_SUPERADMIN")  ){
                        return true;
                }
                if($this->security->isGranted("ROLE_ADMIN") && in_array($subject->getCompany(), $companies) ){
                    return true;
                }
                if(!$this->security->isGranted("ROLE_ADMIN") && ($user === $subject)){return true;}
                break;
            case self::EDIT_PASSWORD:
                if($user === $subject){return true;}
                break;
            case self::CREATE_EMPLOYEE:
                if($this->security->isGranted("ROLE_SUPERADMIN")  ){
                    return true;
                }
                if($this->security->isGranted("ROLE_ADMIN") && in_array($subject->getCompany(), $companies)  ){
                    return true;
                }
                break;
        }

        return false;
    }
}
