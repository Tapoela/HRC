<?php

namespace Config;

class UserValidation
{
    public static function profileRules()
    {
        return [
            'cell' => 'required|regex_match[/^[0-9]{10}$/]',
            'idnumber' => 'permit_empty|regex_match[/^[0-9]{13}$/]',
            'birthdate' => 'permit_empty|valid_date[Y-m-d]',
        ];
    }
}
