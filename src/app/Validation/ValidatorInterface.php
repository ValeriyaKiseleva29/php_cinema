<?php

namespace App\Validation;

interface ValidatorInterface
{
    public function validate($value): bool;
}
