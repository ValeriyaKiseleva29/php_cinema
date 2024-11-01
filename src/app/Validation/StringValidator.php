<?php
namespace App\Validation;

class StringValidator implements ValidatorInterface
{
    public function validate($value): bool
    {
        return is_string($value) && !empty($value) && preg_match('/[a-zA-Zа-яА-Я]/u', $value);
    }
}
