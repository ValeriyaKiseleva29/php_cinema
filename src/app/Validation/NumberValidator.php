<?php
namespace App\Validation;

class NumberValidator implements ValidatorInterface
{
    public function validate($value): bool
    {
        return is_numeric($value) && $value >= 14 && $value <= 100;
    }
}
