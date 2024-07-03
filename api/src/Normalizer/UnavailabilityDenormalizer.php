<?php

namespace App\Normalizer;

use App\Entity\Company;
use App\Entity\Unavailability;
use App\Entity\User;
use App\Repository\CompanyRepository;
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
        protected CompanyRepository $companyRepository,
    ) {
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): mixed
    {
        $userconnected = $this->security->getUser();
        assert($userconnected instanceof User);

        $unavailability = $this->normalizer->denormalize($data, $type, $format, $context);
        assert($unavailability instanceof Unavailability);



        if($context['groups'][0] === "add-unavailability"){
            $companyData = $data['company'];
            $userData = $data['user'];
            if($userData !== null && $companyData !== null){
                $companyData = null;
            }
            if($companyData !== null){
                $parts = explode("/", $companyData);
                $id = end($parts);
                $company = $this->companyRepository->find($id);
                $unavailability->setCompany($company);
                $unavailability->setUser(null);
            }
            if($userData !== null){
                $parts = explode("/", $userData);
                $id = end($parts);
                $user = $this->userRepository->find($id);
                $unavailability->setUser($user);
                $unavailability->setCompany(null);
            }

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

}
