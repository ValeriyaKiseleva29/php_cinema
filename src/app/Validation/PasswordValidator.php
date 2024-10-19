<?php

namespace App\Validation;

class PasswordValidator implements ValidatorInterface
{
    public function validate($value): bool
    {
        if (strlen($value) < 8) {
            return false;
        }

        if (!preg_match('/[0-9]/', $value)) {
            return false;
        }

        return true;
    }
}
