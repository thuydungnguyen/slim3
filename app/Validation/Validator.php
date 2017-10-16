<?php

namespace App\Validation;


use Slim\Http\Request;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    protected $errors;

    public function validate(Request $request, $rules = [])
    {
        foreach ($rules as $field => $rule) {
            try {
                $rule->setName(ucfirst($field))->assert($request->getParam($field));
            } catch ( NestedValidationException $e) {
                if($field === 'g-recaptcha-response'){
                    $this->errors['captcha'] = $e->getMessages();
                }else{
                    $this->errors[$field] = $e->getMessages();
                }
            }
        }

        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function failed()
    {
        return !empty($this->errors);
    }

}