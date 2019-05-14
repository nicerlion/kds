<?php

namespace App\Exports;

use App\Invoice;
use Maatwebsite\Excel\Concerns\FromArray;
use App\Iuser;
use App\IuserType;
use App\Branch;
use Carbon\Carbon;

class UsersExport implements FromArray
{
    public function array(): array
    {
        try {
            $iusers = Branch::select('iusers.*', 'item_iuser.type_id', 'branches.id as branche_id', 'branches.name as branche_name')
            ->leftjoin('items', 'branches.id', '=', 'items.branch_id')
            ->leftjoin('item_iuser', 'items.id', '=', 'item_iuser.item_id')
            ->leftjoin('iusers', 'item_iuser.iuser_id', '=', 'iusers.id')
            ->where('branches.sarlaf', '=', 1)
            ->where('iusers.sarlaf', '=', 0)
            ->orWhere('iusers.sarlaf_duedate', '<', now())
            ->orderBy('iusers.id', 'desc')
            ->get();
        } catch (\Exception $e) {
            $iusers = array();
        }

        $types = IuserType::pluck('name', 'id');
        $userdata = array();

        $response = array (
            array (
                0 => 'Identifación',
                1 => 'Nombre',
                2 => 'Tipo',
                3 => 'Ramo',
                4 => 'Status',
                5 => 'Fecha de Vencimiento'
            )
        );

        foreach ($iusers as $key => $iuser) {
            
            if (isset($response[$iuser->id])) {
                if (!(isset($userdata[$iuser->id]['types'][$iuser->type_id]))) {
                    $response[$iuser->id][2] = $response[$iuser->id][2] . ' | ' . $types[$iuser->type_id];
                    $userdata[$iuser->id]['types'][$iuser->type_id] = '';
                }
                if (!(isset($userdata[$iuser->id]['branches'][$iuser->branche_id]))) {
                    $response[$iuser->id][3] = $response[$iuser->id][3] . ' | ' . $iuser->branche_name;
                    $userdata[$iuser->id]['branches'][$iuser->branche_id] = '';
                }
            } else {
                $response[$iuser->id] = array(
                    0 => $iuser->document,
                    1 => $iuser->name,
                    2 => $types[$iuser->type_id],
                    3 => $iuser->branche_name,
                    4 => ($iuser->sarlaf == 0) ? 'No tiene' : 'Vencido',
                    5 => ($iuser->sarlaf_duedate == null) ? null : Carbon::parse($iuser->sarlaf_duedate)->format('d/m/Y')
                );
                $userdata[$iuser->id]['types'][$iuser->type_id] = '';
                $userdata[$iuser->id]['branches'][$iuser->branche_id] = '';
            }
        }

        return $response;
    }
}