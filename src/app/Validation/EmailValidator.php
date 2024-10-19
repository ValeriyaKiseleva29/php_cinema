<?php
namespace App\Validation;

class EmailValidator implements ValidatorInterface
{
    public function validate($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
