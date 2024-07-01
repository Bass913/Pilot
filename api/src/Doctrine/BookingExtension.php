<?php

namespace App\Doctrine;

use App\Entity\Booking;
use App\Entity\Company;
use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Metadata\Operation;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\User;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BookingExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private Security $security;
    private RequestStack $requestStack;
    public function __construct(Security $security, RequestStack $requestStack)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
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

            $request = $this->requestStack->getCurrentRequest();
            if (!$request) {
                throw new \RuntimeException('No current request.');
            }
            $companyIdRequest = $request->attributes->get('id');

            $adminCompanies = $user->getCompanies();
            $found = false;
            $currentCompany = null;
            foreach ($adminCompanies as $company) {
                assert($company instanceof Company);
                if ($company->getId() == $companyIdRequest) {
                    $found = true;
                    $currentCompany = $company;
                    break;
                }
            }
            if(!$found){
                throw new AccessDeniedException("vous n'avez pas les droits pour visualiser les RDV de cette entreprise");
            }

            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->andWhere(sprintf('%s.company = :current_company', $rootAlias))
                ->setParameter('current_company', $currentCompany);
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
