<?php

namespace App\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Request;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


class RequestStateProcessor implements ProcessorInterface
{

    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        #[Autowire(service: 'api_platform.doctrine.orm.state.remove_processor')]
        private ProcessorInterface $removeProcessor,
        private MailerInterface $mailer,
    ) {
    }
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($operation instanceof DeleteOperationInterface) {
            return $this->removeProcessor->process($data, $operation, $uriVariables, $context);
        }

        $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        $this->sendRequestMail($data);

        return $result;
    }

    private function sendRequestMail(Request $request): void
    {
        $emailClient = (new TemplatedEmail())
            ->from('challenge-pilot@gmail.com')
            ->cc("wtr.esgi@gmail.com")
            ->to($request->getEmail())
            ->subject("Demande d'ajout d'établissement")
            ->htmlTemplate('emails/request.html.twig')
            ->context([
                'firstname' => $request->getFirstname(),
            ]);
        $this->mailer->send($emailClient);
    }
}
