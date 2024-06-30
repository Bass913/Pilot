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

class BookingExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (Booking::class !== $resourceClass) {
            return;
        }

        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return;
        }

        if($this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted('ROLE_SUPERADMIN')){
            $company = $user->getCompany()->getId();
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->andWhere(sprintf('%s.company = :current_company', $rootAlias))
                ->setParameter('current_company', $company);
        }

    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = []
    ): void {

            $this->addWhere($queryBuilder, $resourceClass);

    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        ?Operation $operation = null,
        array $context = []
    ): void {
        $this->addWhere($queryBuilder, $resourceClass);
    }
}
