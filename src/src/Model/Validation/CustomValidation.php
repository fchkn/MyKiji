<?php
namespace App\Model\Validation;
use Cake\Validation\Validation;

class CustomValidation extends Validation {

    public static function notSpace($check) {
        return (bool) !preg_match('/^.*\s.*|.*　.*$/', $check);
    }

    public static function alphaNumericCustom($check) {
        return (bool) preg_match('/^[a-zA-Z0-9]+$/', $check);
    }
}