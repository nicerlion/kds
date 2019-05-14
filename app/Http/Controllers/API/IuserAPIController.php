<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseAPIController as APIBaseController;
use App\Iuser;
use App\IuserType;

class IuserAPIController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        //$iuserType = 2;

        // $iusers = Iuser::where('type','=', $iuserType)->get();
        
        // return $this->sendResponse($iusers->toArray(), 'Posts retrieved successfully.');

        //$iuser = Iuser::all();

        //dd($iuser);

        // $iuser = Iuser::find(1);
        // echo $iuser->iusertypes;

        // $iusers = IuserType::find(1);
        // echo $iusers;

        // foreach ($iuser->iusertypes as $type) {
        //     //echo 'working';

        //     echo $type->pivot->name;
        //     // return $this->sendResponse($type->pivot->name, 'Posts retrieved successfully.');
        // }


        // foreach ($iuser->iusertypes as $type) {
        //     echo $type->pivot->name;
        //     return $this->sendResponse($type->pivot->name->toArray(), 'Posts retrieved successfully.');
        // }
        
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
        $iuser = Iuser::find($id);

        if (is_null($iuser)) {

            return $this->sendError('User not found.');

        }
        
        return $this->sendResponse($iuser->toArray(), 'User retrieved successfully.');
        
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

    public function getIusersById($id) {

        //Brings the users with a specific Type
        //$iusers = Iuser::with('iusertypes')->get();

        $iusers = Iuser::join('iuser_iuser_type', 'iusers.id', '=', 'iuser_iuser_type.iuser_id')->where('iuser_iuser_type.iuser_type_id',$id)->get();
        return $this->sendResponse($iusers->toArray(), 'Posts retrieved successfully.');
        
    }
}
