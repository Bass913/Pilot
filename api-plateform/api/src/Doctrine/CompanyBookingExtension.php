<?php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use App\Entity\Booking;

final class CompanyBookingExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        Operation $operation = null,
        array $context = []
    ): void {
        $this->addFilterConditions($queryBuilder, $resourceClass, $context);
    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        Operation $operation = null,
        array $context = []
    ): void {
        $this->addFilterConditions($queryBuilder, $resourceClass, $context);
    }

    private function addFilterConditions(QueryBuilder $queryBuilder, string $resourceClass, array $context): void
    {

        if ($resourceClass !== Booking::class) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];


        if (isset($context['filters']['company'])) {
            $queryBuilder->join("$rootAlias.companyService", 'cs')
                ->join('cs.company', 'c')
                ->andWhere('c.id = :company')
                ->setParameter('company', $context['filters']['company']);
        }

        if (isset($context['filters']['employee'])) {
            $queryBuilder->andWhere("$rootAlias.employee = :employee")
                ->setParameter('employee', $context['filters']['employee']);
        }
    }
}
