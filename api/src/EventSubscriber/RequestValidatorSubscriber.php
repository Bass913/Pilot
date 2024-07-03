<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\Request as RequestEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class RequestValidatorSubscriber implements EventSubscriberInterface
{
    private $entityManager;
    private $mailer;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->passwordHasher = $passwordHasher;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onKernelView', EventPriorities::POST_VALIDATE],
        ];
    }

    public function onKernelView(ViewEvent $event)
    {
        $request = $event->getRequest();
        $method = $request->getMethod();
        $route = $request->attributes->get('_route');

        if ('_api_/requests/{id}/validate_post' === $route && 'POST' === $method) {
            $requestId = $request->attributes->get('id');
            $requestData = $this->entityManager->getRepository(RequestEntity::class)->find($requestId);


            if (!$requestData) {
                throw new NotFoundHttpException('Request not found');
            }

            // Récupérer le DTO ValidateRequestInput depuis la requête
            $validateRequestInput = $event->getControllerResult();
            $status = $validateRequestInput->status;

            switch ($status) {
                case true:
                    $this->validateRequest($requestData);
                    break;
                case false:
                    $this->refuseRequest($requestData);
                    break;
                default:
                    throw new BadRequestHttpException('Invalid status value');
            }
        }
    }

    public function validateRequest(RequestEntity $requestData)
    {
        if ($requestData->getIsValidated()) {
            throw new BadRequestHttpException('This request has already been validated.');
        }

        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $requestData->getEmail()]);
        if ($existingUser) {
            throw new \RuntimeException('A user with this email already exists.');
        }

        $user = new User();
        $user->setEmail($requestData->getEmail());
        $user->setFirstname($requestData->getFirstname());
        $user->setLastname($requestData->getLastname());
        $user->setPhone($requestData->getPhone());
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setActive(false);


        // Générer un mot de passe temporaire
        $temporaryPassword = bin2hex(random_bytes(4));
        $encodedPassword = $this->passwordHasher->hashPassword($user, $temporaryPassword);
        $user->setPassword($encodedPassword);
        $this->entityManager->persist($user);


        $requestData->setIsValidated(true);

        $this->entityManager->flush();
        $this->sendValidationEmail($user, $temporaryPassword);
    }

    public function refuseRequest(RequestEntity $requestData)
    {
        $this->entityManager->remove($requestData);
        $this->entityManager->flush();
        $this->sendRefusalEmail($requestData);
    }

    public function sendValidationEmail(User $user, string $temporaryPassword)
    {
        date_default_timezone_set('Europe/Paris');
        $currentHour = date('H:i');
        $greetings = ($currentHour >= '17:30') ? "Bonsoir" : "Bonjour";

        $message = (new TemplatedEmail())
            ->from('challengepilot@gmail.com')
            ->to($user->getEmail())
            ->cc("wtr.esgi@gmail.com")
            ->subject("Validation de demande d'ajout d'établissement")
            ->htmlTemplate('emails/requestvalidate.html.twig')
            ->context([
                'firstname' => $user->getFirstname(),
                'greetings' => $greetings,
                'password' =>  $temporaryPassword,
            ]);


        $this->mailer->send($message);
    }

    private function sendRefusalEmail(RequestEntity $requestData)
    {

        $message = (new TemplatedEmail())
            ->from('challengepilot@gmail.com')
            ->to($requestData->getEmail())
            ->cc("wtr.esgi@gmail.com")
            ->subject("Refus de demande d'ajout d'établissement")
            ->htmlTemplate('emails/requestrefuse.html.twig')
            ->context([
                'firstname' => $requestData->getFirstname(),
                'lastname' => $requestData->getLastname(),
            ]);

        $this->mailer->send($message);
    }
}
