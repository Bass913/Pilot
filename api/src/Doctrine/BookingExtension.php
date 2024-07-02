<?php

namespace App\Doctrine;

use App\Entity\Booking;
use App\Entity\Company;
use App\Repository\BookingRepository;
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

    private BookingRepository $bookingRepository;
    public function __construct(Security $security, RequestStack $requestStack, BookingRepository $bookingRepository)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
        $this->bookingRepository = $bookingRepository;
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass, ?Operation $operation = null ): void
    {
        if (Booking::class !== $resourceClass) {
            return;
        }

        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return;
        }


        if($this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted('ROLE_SUPERADMIN')){
            $companiesBookingUrl = "_api_/api/companies/{id}/bookings_get_collection";
            //$detailsBookingUrl = "_api_/api/bookings/{id}_get";

            $currentCompany = null;
            if($operation->getName() === $companiesBookingUrl ){


                $request = $this->requestStack->getCurrentRequest();
                if (!$request) {
                    throw new \RuntimeException('No current request.');
                }
                $idRequest = $request->attributes->get('id');

                /*if($operation->getName() === $detailsBookingUrl){
                    $booking = $this->bookingRepository->find($idRequest);
                    $idRequest = $booking->getCompany()->getId();
                }*/
                $adminCompanies = $user->getCompanies();
                $found = false;
                foreach ($adminCompanies as $company) {
                    assert($company instanceof Company);
                    if ($company->getId() == $idRequest) {
                        $found = true;
                        $currentCompany = $company;
                        break;
                    }
                }
                if(!$found){
                    throw new AccessDeniedException("vous n'avez pas les droits requis");
                }
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->andWhere(sprintf('%s.company = :current_company', $rootAlias))
                    ->setParameter('current_company', $currentCompany);
            }




        }

    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = []
    ): void {
            $this->addWhere($queryBuilder, $resourceClass, $operation);

    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        ?Operation $operation = null,
        array $context = []
    ): void {
        $this->addWhere($queryBuilder, $resourceClass, $operation);
    }
}
