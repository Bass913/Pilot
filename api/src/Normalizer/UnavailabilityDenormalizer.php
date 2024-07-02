<?php

namespace App\Normalizer;

use App\Entity\Company;
use App\Entity\Unavailability;
use App\Entity\User;
use App\Repository\UserRepository;
use PhpCsFixer\Tokenizer\Analyzer\CommentsAnalyzer;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UnavailabilityDenormalizer implements DenormalizerInterface
{
    public function __construct(
        protected Security $security,
        protected PasswordHasherFactoryInterface $hasher,
        protected ObjectNormalizer $normalizer,
        protected RequestStack $requestStack,
        protected UserRepository $userRepository,
    ) {
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): mixed
    {
        $userconnected = $this->security->getUser();
        assert($userconnected instanceof User);

        $unavailability = $this->normalizer->denormalize($data, $type, $format, $context);
        assert($unavailability instanceof Unavailability);


        $isAdmin = $this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted('ROLE_SUPERADMIN');
        /*if($context['groups'][0] === "update-unavailability" && $isAdmin ) {
            $request = $this->requestStack->getCurrentRequest();
            if (!$request) {
                throw new \RuntimeException('No current request.');
            }
            $companyIdRequest = $request->attributes->get('id');
            $admin = $this->security->getUser();
            assert($admin instanceof User);
            $adminCompanies = $admin->getCompanies();

            $found = false;
            foreach ($adminCompanies as $company) {
                assert($company instanceof Company);
                if ($company->getId() == $companyIdRequest) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                throw new AccessDeniedException("Vous n'avez pas les droits requis pour modifier cet établissement");
            }
            return $company;
        }*/




        if($context['groups'][0] != "add-unavailability"){
            return $unavailability;
        }

        $userData = $data['user'] ?? null;
        $companyData = $data['company'] ?? null;

        if($userData !== null && $companyData !== null){
            $companyData = null;
        }
        if($this->security->isGranted("ROLE_SUPERADMIN")){
            return $unavailability;
        }

        if($isAdmin){
            if($companyData !== null){
                $parts = explode("/", $companyData);
                $id = end($parts);
                $selectedCompany = $this->checkIsCompanyAdmin($userconnected, $id);

                $unavailability->setCompany($selectedCompany);
                $unavailability->setUser(null);
            }else{
                $parts = explode("/", $userData);
                $id = end($parts);
                $selectedUser = $this->checkUserCompanyInAdminCompanies($userconnected, $id);
                $unavailability->setUser($selectedUser);
                $unavailability->setCompany(null);
            }
        }else{
            $parts = explode("/", $userData);
            $id = end($parts);
            $this->checkIsUserConnected($userconnected,$id);
            $unavailability->setUser($userconnected);
            $unavailability->setCompany(null);
        }

        return $unavailability;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $type === Unavailability::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Unavailability::class => true,
        ];
    }

    public function checkIsCompanyAdmin($admin, $companyId) : Company{

        $adminCompanies = $admin->getCompanies();
        $selectedCompany = null;
        $found = false;

        foreach ($adminCompanies as $company) {
            assert($company instanceof Company);
            if ($company->getId() == $companyId) {
                $found = true;
                $selectedCompany = $company;
                break;
            }
        }

        if (!$found) {
            throw new AccessDeniedException("Vous n'avez pas les droits requis");
        }

        return $selectedCompany;
    }

    public function checkUserCompanyInAdminCompanies(User $admin, $userDataId): User
    {
        $userData = $this->userRepository->find($userDataId);
        $adminCompanies = $admin->getCompanies();
        $userCompany = $userData->getCompany();
        $found = false;

        foreach ($adminCompanies as $company) {
            assert($company instanceof Company);
            if ($company->getId() == $userCompany->getId()) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new AccessDeniedException("Vous n'avez pas les droits requis");
        }
        return $userData;
    }

    public function checkIsUserConnected($userConnected, $userId): void{
        $userData = $this->userRepository->find($userId);
        if($userData !== $userConnected){
            throw new AccessDeniedException("Vous n'avez pas les droits requis");
        }

    }
}
