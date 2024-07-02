<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\SMSReminder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Twilio\Rest\Client;

class SMSReminderProcessor implements ProcessorInterface
{

    private $twilioSid;
    private $twilioAuthToken;
    private $twilioPhoneNumber;

    public function __construct(ParameterBagInterface $params)
    {
        $this->twilioSid = $params->get('twilio.sid');
        $this->twilioAuthToken = $params->get('twilio.auth_token');
        $this->twilioPhoneNumber = $params->get('twilio.phone_number');
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof SMSReminder) {
            $this->sendSMSReminder($data);
        }
    }

    private function sendSMSReminder(SMSReminder $reminder)
    {
        $client = new Client($this->twilioSid, $this->twilioAuthToken);

        $message = sprintf(
            'Bonjour %s, ceci est un rappel pour votre rendez-vous prévu pour le %s.',
            $reminder->firstname,
            $reminder->startDate->format('d/m/Y H:i')
        );


        $client->messages->create(
            $reminder->phoneNumber,
            [
                'from' => $this->twilioPhoneNumber,
                'body' => $message,
            ]
        );
    }
}
