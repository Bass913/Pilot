<?php

namespace App\Normalizer;

use App\Entity\Company;
use App\Entity\Schedule;
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

class ScheduleDenormalizer implements DenormalizerInterface
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

        $schedule = $this->normalizer->denormalize($data, $type, $format, $context);
        assert($schedule instanceof Schedule);




        if($context['groups'][0] === "add-schedule"){
            $companyData = $data['company'];
            $userData = $data['user'];
            if($userData !== null && $companyData !== null){
                $companyData = null;
            }
            if($companyData !== null){
                $parts = explode("/", $companyData);
                $id = end($parts);
                $company = $this->companyRepository->find($id);
                $schedule->setCompany($company);
                $schedule->setUser(null);
            }
            if($userData !== null){
                $parts = explode("/", $userData);
                $id = end($parts);
                $user = $this->userRepository->find($id);
                $schedule->setUser($user);
                $schedule->setCompany(null);
            }

        }

        return $schedule;
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
