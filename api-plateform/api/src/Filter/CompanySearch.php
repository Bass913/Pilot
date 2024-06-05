<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\PropertyInfo\Type;

final class CompanySearch extends AbstractFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        if ('search' !== $property) {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->andWhere($queryBuilder->expr()->orX(
                $queryBuilder->expr()->like("$alias.name", ':search'),
                $queryBuilder->expr()->like("$alias.address", ':search'),
                $queryBuilder->expr()->like("speciality.name", ':search'),
                $queryBuilder->expr()->like("service.name", ':search')
            ))
            ->setParameter('search', "%$value%");

        $queryBuilder->leftJoin("$alias.speciality", 'speciality');
        $queryBuilder->leftJoin("$alias.companyServices", 'companyService');
        $queryBuilder->leftJoin('companyService.service', 'service');
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'search' => [
                'property' => null,
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
                'swagger' => [
                    'description' => 'Filter by a search term in name, speciality name, or service name',
                ],
            ],
        ];
    }
}
