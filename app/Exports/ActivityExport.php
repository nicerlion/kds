<?php

namespace App\Exports;

use App\Invoice;
use Maatwebsite\Excel\Concerns\FromArray;
use App\Item;
use App\Branch;
use App\Iuser;
use App\InsuranceCo;
use App\IuserType;
use App\BStatus;
use App\Responsible;
use App\Record;
use App\User;

use Carbon\Carbon;

class ActivityExport implements FromArray
{
    public function array(): array
    {
        try {
            $records = Record::select('records.*','users.*')
                    ->leftjoin('users', 'records.user_id', '=', 'users.id')
                    ->get();

        } catch (\Exception $e) {
            $records = array();
        }

        
        $response = array (
            array (
                0 => 'Usuario',
                1 => 'Comentario',
                2 => 'Modelo',
                3 => 'Modelo Id',
                4 => 'Fecha'
            )
        );

        foreach ($records as $key => $a) {
            
                $response[] = array(
                    0 => $a->name,
                    1 => $a->comments,
                    2 => $a->model,
                    3 => $a->model_id,
                    4 => $a->created_at
                );
        }

        return $response;
    }
}