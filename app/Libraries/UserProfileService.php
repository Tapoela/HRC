<?php

namespace App\Libraries;

class UserProfileService
{
    public function rules()
    {
        return [

            'name'      => 'required|min_length[2]',
            'surname'   => 'permit_empty',
            'email'     => 'required|valid_email',

            'cell'      => 'permit_empty|regex_match[/^[0-9]{10}$/]',
            'idnumber'  => 'permit_empty|regex_match[/^[0-9]{13}$/]',
            'birthdate' => 'permit_empty|valid_date[Y-m-d]',

            'height'    => 'permit_empty|numeric',
            'weight'    => 'permit_empty|numeric',

        ];
    }

    public function calculateProfileCompleted($data)
    {
        $required = ['name','email','cell','idnumber','birthdate'];

        foreach($required as $field){
            if(empty($data[$field])) {
                return 0;
            }
        }

        return 1;
    }
}
