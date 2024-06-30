<?php

namespace App\Normalizer;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserDenormalizer implements DenormalizerInterface
{
    public function __construct(
        protected Security $security,
        protected PasswordHasherFactoryInterface $hasher,
        protected ObjectNormalizer $normalizer
    ) {
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): mixed
    {


        $user = $this->normalizer->denormalize($data, $type, $format, $context);
        if(!in_array($context['groups'][0], ["user:create", "user:update:password"])){
            return $user;
        }

       /* if($context['groups'] !== "user:create" && $context['groups'] !== "user:update:password"){
            return $user;
        }*/


        assert($user instanceof User);

        if($context['groups'] === "user:create"){
            $user->setFirstname($data['firstname'] ?? null);
            $user->setLastname($data['lastname'] ?? null);
            $user->setPhone($data['phone'] ?? null);
            $user->setEmail($data['email'] ?? null);
            $user->setRoles(['ROLE_USER']);
        }

        $plainPassword = $user->getPassword();

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
