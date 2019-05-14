<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InsuranceCo;

class InsuranceCoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $insurancecos = insuranceCo::all();
        return view('companies.index', compact('insurancecos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=> 'required'
        ]);

        InsuranceCo::create($request->all());

        return redirect('insurance-companies');
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
        $insuranceco = InsuranceCo::findOrFail($id);
        return view('companies.edit', compact('insuranceco'));
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

        $this->validate($request,[
            'name'=> 'required'
        ]);

        $insuranceco = insuranceCo::findOrFail($id);
        $insuranceco->update($request->all());

        return redirect('insurance-companies');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $insuranceco = insuranceCo::findOrFail($id);
        $insuranceco->delete();
        
        return redirect('insurance-companies');
    }

    public function deleteform($id){

        $insuranceco = insuranceCo::findOrFail($id);
        return view('companies.delete', compact('insuranceco'));
    }
}
