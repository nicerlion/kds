<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\API\BaseAPIController as APIBaseController;
use App\Item;
use App\Iuser;
use App\InsuranceCo;
use App\BStatus;
use App\Branch;
use App\Responsible;
use App\IuserType;
use Carbon\Carbon;

class SearchAPIController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search($term, $page) {
        $limit = 8;
        $offset = $limit * $page;
        $output = Item::where('item_number','like','%'.$term.'%')
            ->orWhere('plate', 'like', '%'.$term.'%')
            ->orWhereHas('iusers', function($q) use ($term){
            return $q->where('name','like','%'. $term . '%');
        })->orWhereHas('iusers', function($q) use ($term){
            return $q->where('document','like','%'. $term . '%');
        })->orWhereHas('branches', function($q) use ($term){
            return $q->where('name','like','%'. $term . '%');
        })->orWhereHas('companies', function($q) use ($term){
            return $q->where('name','like','%'. $term . '%');
        })
        ->offset($offset)
        ->limit($limit)
        ->get();

        if (is_null($output)) {
            return $this->sendError('Item not found.');
        } else {
            $response = array();

            try {
                $offset = $limit * ($page + 1);
                $nextpage = Item::where('item_number','like','%'.$term.'%')
                    ->orWhere('plate', 'like', '%'.$term.'%')
                    ->orWhereHas('iusers', function($q) use ($term){
                    return $q->where('name','like','%'. $term . '%');
                })->orWhereHas('iusers', function($q) use ($term){
                    return $q->where('document','like','%'. $term . '%');
                })->orWhereHas('branches', function($q) use ($term){
                    return $q->where('name','like','%'. $term . '%');
                })->orWhereHas('companies', function($q) use ($term){
                    return $q->where('name','like','%'. $term . '%');
                })
                ->offset($offset)
                ->limit(1)
                ->get();

                $response['more'] = $nextpage->toArray();

            } catch (\Exception $e) {                
                $response['more'] = array();
            }
            
            $response['results'] = $output->toArray();
            return $this->sendResponse($response, 'Item retrieved successfully.');
        }
    }

    public function searchiuser($doc) {
        
        $response = array('user' => array(), 'sarlaf' => null, 'items' => array(), 'types' => array());

        $iuserData = Iuser::where('document', '=', $doc)->get();
        $sarlaf_content = '';
        if (isset($iuserData[0])) {

            if ($iuserData[0]->image_path) {
                $files = json_decode($iuserData[0]->image_path, true);
                if ($files){
                    foreach ($files as $file) {
                        if (isset($file)) {
                            $name = (isset($file['oriname'])) ? $file['oriname'] : $file['name'];
                            $path = Storage::disk('s3')->temporaryUrl($file["fullpath"], now()->addMinutes(10));
                            if ($file['type'] == 'pdf' or $file['type'] == 'PDF') {
                                $sarlaf_content .= '<a href="' . $path . '" class="portfolio-box" target="_blank">
                                    <span class="archive-item">
                                        <span class="row align-items-center">
                                            <span class="col-auto"><i class="fas fa-file-pdf"></i></span>
                                            <span class="col">' . $name . '</span>
                                        </span>
                                        <span class="boder-archive"></span>
                                    </span>';
                                } else {
                                    $sarlaf_content .= '<a href="' . $path . '" class="portfolio-box img-document" target="_blank" style=" background-image: url(' . $path . '); ">
                                    <span class="archive-item">
                                        <span class="row align-items-center">
                                            <span class="col-auto"><i class="fas fa-image"></i></span>
                                            <span class="col">' . $name . '</span>
                                        </span>
                                        <span class="boder-archive"></span>
                                    </span>';                            
                                }
                                $sarlaf_content .= '</a>';
                            $response['sarlaf'] = $sarlaf_content;
                        }
                    }
                }              
            }

            $sarlaf_duedate = $iuserData[0]->sarlaf_duedate;
            $response['sarlaf_duedate'] = ($sarlaf_duedate == null) ? null : Carbon::parse($sarlaf_duedate)->format('d/m/Y');
            
            $id = $iuserData[0]->id;
            try {
                $items = Item::select('items.*', 'bs.name as bs_name')
                ->leftjoin('item_iuser', 'items.id', '=', 'item_iuser.item_id')
                ->leftjoin('bs', 'items.bs_id', '=', 'bs.id')
                ->where('item_iuser.iuser_id', '=', $id)
                ->orderBy('items.id', 'desc')
                ->distinct('items')
                ->get();
                $response['items'] = $items->toArray();
            } catch (\Exception $e) {
                $response['items'] = array();
            }

            $data = array();

            if (isset($response['items'][0])) {
                foreach($items as $item) {
                    try {
                        $type = Item::select('iuser_types.name')
                        ->leftjoin('item_iuser', 'items.id', '=', 'item_iuser.item_id')
                        ->leftjoin('iuser_types', 'item_iuser.type_id', '=', 'iuser_types.id')
                        ->where([
                                ['item_iuser.iuser_id', '=', $id],
                                ['item_iuser.item_id', '=', $item->id]
                            ])
                        ->orderBy('items.id', 'desc')
                        ->distinct('item_iuser')
                        ->get();
                        
                        $data[$item->id] = $type->toArray();
                    } catch (\Exception $e) {
                        $data[$item->id] = array();
                    }
                }
            }
            
            $response['user'] = $iuserData->toArray();
            $response['types'] = $data;
            $response['items'] = $items->toArray();

            return $this->sendResponse($response, 'Item retrieved successfully.');

        } else {
            return $this->sendResponse($response, 'Item retrieved successfully.');
        }

        

    }

    public function searchiuserdetail($doc, $id) {
        
        try {
            $bs = Iuser::where([
                ['document', '=', $doc],
                ['id', '!=', $id]
            ])->get();
            $response = $bs->toArray();
        } catch (\Exception $e) {
            $response = array();
        }

        return $this->sendResponse($response, 'Item retrieved successfully.');

    }

    public function searchplate($plate) {
        
        try {
            $items = Item::select('items.*', 'bs.name as bs_name')
            ->leftjoin('bs', 'items.bs_id', '=', 'bs.id')
            ->where('items.plate', '=', $plate)
            ->orderBy('items.id', 'desc')
            ->distinct('items')
            ->get();
            $response = $items->toArray();
        } catch (\Exception $e) {
            $response = array();
        }

        return $this->sendResponse($response, 'Item retrieved successfully.');

    }

    public function searchcompany($name, $id) {
        
        try {
            $company = InsuranceCo::where([
                ['name', '=', $name],
                ['id', '!=', $id]
            ])->get();
            $response = $company->toArray();
        } catch (\Exception $e) {
            $response = array();
        }

        return $this->sendResponse($response, 'Item retrieved successfully.');

    }

    public function searchbs($name, $id) {
        
        try {
            $bs = BStatus::where([
                ['name', '=', $name],
                ['id', '!=', $id]
            ])->get();
            $response = $bs->toArray();
        } catch (\Exception $e) {
            $response = array();
        }

        return $this->sendResponse($response, 'Item retrieved successfully.');

    }

    public function searchbranch($name, $id) {
        
        try {
            $bs = Branch::where([
                ['name', '=', $name],
                ['id', '!=', $id]
            ])->get();
            $response = $bs->toArray();
        } catch (\Exception $e) {
            $response = array();
        }

        return $this->sendResponse($response, 'Item retrieved successfully.');

    }

    public function searchresponsible($type, $value, $id) {
        
        try {
            $bs = Responsible::where([
                [$type, '=', $value],
                ['id', '!=', $id]
            ])->get();
            $response = $bs->toArray();
        } catch (\Exception $e) {
            $response = array();
        }

        return $this->sendResponse($response, 'Item retrieved successfully.');

    }

    public function searchiusertype($name, $id) {
        
        try {
            $bs = IuserType::where([
                ['name', '=', $name],
                ['id', '!=', $id]
            ])->get();
            $response = $bs->toArray();
        } catch (\Exception $e) {
            $response = array();
        }

        return $this->sendResponse($response, 'Item retrieved successfully.');

    }
}
