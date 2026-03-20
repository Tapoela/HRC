<?php

namespace App\Services;

use App\Models\TabModel;

class TabService
{

    public function openTab($data)
    {

        $tabModel = new TabModel();

        $data['status'] = 'open';
        $data['balance'] = 0;

        return $tabModel->insert($data);

    }

    public function closeTab($tabId)
    {

        $tabModel = new TabModel();

        return $tabModel->update($tabId,[
            'status'=>'closed'
        ]);

    }

}