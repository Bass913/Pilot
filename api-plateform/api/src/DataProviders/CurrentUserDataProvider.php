<?php

namespace App\DataProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;

class CurrentUserDataProvider implements ProviderInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?User
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('User not found or not authenticated.');
        }

        return $user;
    }
}
