<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class ValidateRequestInput
{
    #[Groups(['request:write'])]
    public ?bool $status;
}
