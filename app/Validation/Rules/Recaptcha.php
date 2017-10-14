<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class Recaptcha extends AbstractRule
{

    public function validate($responseKey)
    {
        $secretKey = "6LccdzQUAAAAAMBfRge-p9qRCw3cwxQfmFHtw33E";
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey";
        $response = json_decode(file_get_contents($url));

        return $response->success;
    }

}