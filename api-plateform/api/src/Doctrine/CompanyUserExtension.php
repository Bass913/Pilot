<?php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\Query\Expr;

final class CompanyUserExtension implements QueryCollectionExtensionInterface
{
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if($resourceClass !== User::class ){
            return;
        }

        if($operation->getName() === "_api_/companies/{id}/employees_get_collection"){
            $this->addWhereEmployees($queryBuilder);
        }
    }



    private function addWhereEmployees(QueryBuilder $queryBuilder,): void
    {
        $users = $queryBuilder->getQuery()->getResult();
        $filteredUsers = array_filter($users, function($user) {
            $roles = $user->getRoles();
            return in_array("ROLE_EMPLOYEE", $roles);
        });

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s IN (:filteredUsers)', $rootAlias));
        $queryBuilder->setParameter('filteredUsers', $filteredUsers);
    }
}
