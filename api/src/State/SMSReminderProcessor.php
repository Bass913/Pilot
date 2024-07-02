<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Booking;
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
        if ($data instanceof Booking) {
            $this->sendSMSReminder($data);
        }
    }

    private function sendSMSReminder(Booking $booking)
    {
        $client = new Client($this->twilioSid, $this->twilioAuthToken);

        $message = sprintf(
            'Bonjour %s, ceci est un rappel pour votre rendez-vous prévu pour le %s.',
            $booking->getClient()->getFirstname(),
            $booking->getStartDate()
        );

        $client->messages->create(
            $booking->getClient()->getPhone(),
            [
                'from' => $this->twilioPhoneNumber,
                'body' => $message,
            ]
        );
    }
}
