<?php
namespace App\Model\Validation;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Validation\Validation;

class CustomValidation extends Validation {

    public static function notSpace($value) {
        return (bool) !preg_match('/^.*\s.*|.*　.*$/', $value);
    }

    public static function alphaNumericCustom($value) {
        return (bool) preg_match('/^[a-zA-Z0-9]+$/', $value);
    }

    public static function matchCurrentPassword($value, $context) {
        return (new DefaultPasswordHasher)->check($value, $context['data']['password_curt_registered']);
    }
}