<?php

namespace App\Validation\Exceptions;

use \Respect\Validation\Exceptions\ValidationException;


class RecaptchaException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Va rugam sa bifati campul Captcha',
        ],
    ];

}