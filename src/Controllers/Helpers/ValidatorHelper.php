<?php

namespace App\Controllers\Helpers;

use App\Tools\Validator;

class ValidatorHelper
{
    public static function getValidatorForContact(array $data = []): Validator
    {
        $validator = new Validator($data);
        $validator->required("firstname", "lastname", "email", "number_phone")
            ->required("address", "city", "country")
            ->length("firstname", 3)
            ->length("lastname", 3)
            ->length("address", 10)
            ->length("city", 2)
            ->length("country", 2)
            ->email("email");
        return $validator;
    }
}