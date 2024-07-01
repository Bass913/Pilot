<?php

namespace App\Doctrine;

use App\Entity\Booking;
use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Metadata\Operation;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\User;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;

class CurrentUserConnectedExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (User::class !== $resourceClass && Booking::class !== $resourceClass) {
            return;
        }

        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.id = :current_user', $rootAlias))
            ->setParameter('current_user', $user->getId());
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = []
    ): void {
        if ($operation && $operation->getName() === '_api_/api/client/{id}/bookings_get') {
            $this->addWhere($queryBuilder, $resourceClass);
        }
        if($operation && $operation->getName() === '_api_/api/users/{id}/companies_get'){
            $this->addWhere($queryBuilder, $resourceClass);
        }
    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        ?Operation $operation = null,
        array $context = []
    ): void {
        if ($operation && $operation->getName() === '_api_/api/me_get') {
            $this->addWhere($queryBuilder, $resourceClass);
        }
        if ($operation && $operation->getName() === '_api_/api/client/{id}/bookings_get') {
            $this->addWhere($queryBuilder, $resourceClass);
        }

        if($operation && $operation->getName() === '_api_/api/users/{id}/companies_get'){
            $this->addWhere($queryBuilder, $resourceClass);
        }

    }
}
