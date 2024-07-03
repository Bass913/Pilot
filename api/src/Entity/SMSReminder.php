<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\State\SMSReminderProcessor;


#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/send-sms-reminder',
            processor: SMSReminderProcessor::class
        )
    ]
)]
class SMSReminder
{
    public string $firstname;
    public \DateTimeInterface $startDate;
    public string $phoneNumber;
}
