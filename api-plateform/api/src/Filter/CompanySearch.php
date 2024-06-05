<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class CompanySearch extends AbstractFilter
{
    private HttpClientInterface $httpClient;

    public function __construct(ManagerRegistry $managerRegistry, HttpClientInterface $httpClient, $requestStack = null, array $properties = null)
    {
        parent::__construct($managerRegistry, $requestStack, $properties);
        $this->httpClient = $httpClient;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        /*if ('search' !== $property) {
            return;
        }*/


        $alias = $queryBuilder->getRootAliases()[0];

        if ($property === 'search') {
            $lowerValue = strtolower($value);

            $queryBuilder
                ->leftJoin("$alias.speciality", 'speciality')
                ->leftJoin("$alias.companyServices", 'companyService')
                ->leftJoin('companyService.service', 'service')
                ->andWhere($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like("LOWER($alias.name)", ':search'),
                    $queryBuilder->expr()->like("LOWER($alias.address)", ':search'),
                    $queryBuilder->expr()->like("LOWER(speciality.name)", ':search'),
                    $queryBuilder->expr()->like("LOWER(service.name)", ':search')
                ))
                ->setParameter('search', "%$lowerValue%");
        }

        /*if ($property === 'address') {

        }*/
    }



    public function getDescription(string $resourceClass): array
    {
        return [
            'search' => [
                'property' => 'search',
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
                'swagger' => [
                    'description' => 'Filter by a search term in name, speciality name, or service name',
                ],
            ],
            'address' => [
                'property' => 'address',
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
                'swagger' => ['description' => 'Filter by address.']
            ]
        ];
    }
}
