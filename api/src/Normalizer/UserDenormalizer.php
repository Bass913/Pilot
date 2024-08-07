<?php

namespace App\Normalizer;

use App\Entity\Company;
use App\Entity\User;
use App\Repository\CompanyRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserDenormalizer implements DenormalizerInterface
{
    public function __construct(
        protected Security $security,
        protected PasswordHasherFactoryInterface $hasher,
        protected ObjectNormalizer $normalizer,
        protected CompanyRepository $companyRepository,
    ) {
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): mixed
    {

        $user = $this->normalizer->denormalize($data, $type, $format, $context);
        if(!in_array($context['groups'][0], ["user:create", "user:update:password", 'user:register'])){
            return $user;
        }


        assert($user instanceof User);

        if (in_array($context['groups'][0], ['user:register', 'user:create'])) {
            $user->setFirstname($data['firstname'] ?? null);
            $user->setLastname($data['lastname'] ?? null);
            $user->setPhone($data['phone'] ?? null);
            $user->setEmail($data['email'] ?? null);
            $user->setActive(true);
            $user->setRoles(['ROLE_USER']);


            if ($context['groups'][0] === 'user:create') {
                $companyIdDataRequest = $data['companyId'];
                $parts = explode("/", $companyIdDataRequest);
                $companyIdData = end($parts);
                $companyUser = $this->companyRepository->find($companyIdData);

                $user->setCompany($companyUser);
                $user->setRoles(['ROLE_USER', 'ROLE_EMPLOYEE']);

            }
        }

        $plainPassword = !empty($user->getPassword()) ? $user->getPassword() : 'test' ;

        if (!empty($plainPassword)) {
            $hasher = $this->hasher->getPasswordHasher($user);
            $hashedPassword = $hasher->hash($plainPassword);
            $user->setPassword($hashedPassword);
        }

        $user->eraseCredentials();
        return $user;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $type === User::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            User::class => true,
        ];
    }
}
