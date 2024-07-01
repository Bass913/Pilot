<?php

namespace App\Normalizer;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CompanyDenormalizer implements DenormalizerInterface
{
    public function __construct(
        protected Security $security,
        protected PasswordHasherFactoryInterface $hasher,
        protected ObjectNormalizer $normalizer
    ) {
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): mixed
    {

        $company = $this->normalizer->denormalize($data, $type, $format, $context);



        /*assert($company instanceof Company);
        $company->setName($data['name'] ?? null);
        $company->setAddress($data['address']?? null);*/





        return $company;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $type === Company::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Company::class => true,
        ];
    }
}
