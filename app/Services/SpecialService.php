<?php

namespace App\Services;

use App\Models\SpecialCreditModel;

class SpecialService
{

    public function redeemDrink($creditId,$qty)
    {

        $creditModel = new SpecialCreditModel();

        $credit = $creditModel->find($creditId);

        if($credit['remaining_drinks'] < $qty)
        {
            throw new \Exception("Not enough drinks");
        }

        $creditModel->update($creditId,[
            'remaining_drinks' =>
            $credit['remaining_drinks'] - $qty
        ]);

    }

}