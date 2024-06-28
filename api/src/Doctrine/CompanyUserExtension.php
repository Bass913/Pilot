<?php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Symfony\Bundle\SecurityBundle\Security;

final class CompanyUserExtension implements QueryCollectionExtensionInterface
{
    public function __construct(
        private Security $security,
    ) {
    }
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        /*var_dump($this->security->isGranted("ROLE_ADMIN"));
        var_dump($this->security->getUser()->getUserIdentifier());

        die();*/
        if ($resourceClass !== User::class) {
            return;
        }

        if ($operation->getName() === "_api_/companies/{id}/employees_get_collection" || $operation->getName() === "_api_/companies/{id}/employees/planning_get_collection") {
            $this->addWhereEmployees($queryBuilder);
        }
    }



    private function addWhereEmployees(QueryBuilder $queryBuilder, ): void
    {
        $users = $queryBuilder->getQuery()->getResult();

        $filteredUsers = array_filter($users, function ($user) {
            $roles = $user->getRoles();
            return $roles === ["ROLE_USER", "ROLE_EMPLOYEE"];
        });

        $filteredUserIds = array_map(function ($user) {
            return $user->getId();
        }, $filteredUsers);

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.id IN (:filteredUserIds)', $rootAlias));
        $queryBuilder->setParameter('filteredUserIds', $filteredUserIds);
    }
}
