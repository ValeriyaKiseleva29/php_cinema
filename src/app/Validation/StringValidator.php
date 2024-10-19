<?php
namespace App\Validation;

class StringValidator implements ValidatorInterface
{
    public function validate($value): bool
    {
        return is_string($value) && !empty($value);
    }
}