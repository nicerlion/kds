<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Iuser;
use App\Branch;
use App\InsuranceCo;
use App\Item;
use App\BStatus;
use App\Responsible;
use App\IuserType;
use App\User;
use App\Record;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $branches = Branch::all()->take(10)->sortBy('name');
        $items = Item::all()->take(10);
        $companies = InsuranceCo::all()->take(10);
        $iusers = Iuser::all()->take(10);

        return view('admin.index',['items' => $items, 'branches' => $branches, 'iusers' => $iusers, 'companies' => $companies]);
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

    public function showcompanies() {
        $companies = InsuranceCo::orderby('name', 'asc')->get();
        return view('admin.companies', compact('companies'));
    }

    public function createcompany() {
        return view('admin.createcompany');
    }

    public function storecompany(Request $request) {

        $user = auth()->user();

        $this->validate(
            $request,
            array('name' => 'required'),
            array(
                'required' => 'El Campo :attribute es Obligatorio.',
                'name.required' => 'El :attribute es Obligatorio.'
            ),
            array('name' => 'Nombre')
        );

        try{

            $ic = InsuranceCo::create($request->all());

            $record = new Record();
            $record['user_id'] = $user->id;
            $record['comments'] = 'Creación de Compania de Seguro';
            $record['model'] = 'insuranceco';
            $record['model_id'] = $ic->id;
            
            $record->save();

            return redirect('admin/companies');
        
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }    

    public function editcompany($id) {
        $company = InsuranceCo::findOrFail($id);
        return view('admin.editcompany', compact('company'));
    }

    public function updatecompany(Request $request, $id) {

        $user = auth()->user();

        $this->validate(
            $request,
            array('name' => 'required'),
            array(
                'required' => 'El Campo :attribute es Obligatorio.',
                'name.required' => 'El :attribute es Obligatorio.'
            ),
            array('name' => 'Nombre')
        );

        try {
            $company = InsuranceCo::findOrFail($id);
            $company->update($request->all());

            $record = new Record();
            $record['user_id'] = $user->id;
            $record['comments'] = 'Actualización de Compania de Seguro';
            $record['model'] = 'insuranceco';
            $record['model_id'] = $company->id;
            
            $record->save();

            return redirect('admin/companies');
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function createBranch() {
        return view('admin.createbranch');
    }

    public function storebranch(Request $request) {

        $user = auth()->user();

        $this->validate(
            $request,
            array('name' => 'required'),
            array(
                'required' => 'El Campo :attribute es Obligatorio.',
                'name.required' => 'El :attribute es Obligatorio.'
            ),
            array('name' => 'Nombre')
        );

        $sarlaffield = $request->input('sarlaf');
        $sarlaft = 0;

        if ($sarlaffield != null) {

            $sarlaft = 1;

        }

        try {
            
            $newBranch = new Branch();
            $newBranch['name'] = $request->input('name');
            $newBranch['icon'] = null;
            $newBranch['sarlaf'] = $sarlaft;

            $newBranch->save();
            
            $record = new Record();
            $record['user_id'] = $user->id;
            $record['comments'] = 'Creación de Ramo';
            $record['model'] = 'branch';
            $record['model_id'] = $newBranch->id;
            
            $record->save();

            return redirect('admin/branches');
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function showbranches() {
        $branches = Branch::orderby('name', 'asc')->get();
        return view('admin.branches', compact('branches'));
    }

    public function editbranch($id) {
        $branch = Branch::findOrFail($id);
        return view('admin.editbranch', compact('branch'));
    }

    public function updatebranch(Request $request, $id) {
        
        $user = auth()->user();

        $this->validate(
            $request,
            array('name' => 'required'),
            array(
                'required' => 'El Campo :attribute es Obligatorio.',
                'name.required' => 'El :attribute es Obligatorio.'
            ),
            array('name' => 'Nombre')
        );
        
        $updateBranch = [];
        $updateBranch['name'] = $request->input('name');

        $sarlaf = $request->input('sarlaf');

        if ($sarlaf == null) {
            $updateBranch['sarlaf'] = 0;
        } else {
            $updateBranch['sarlaf'] = 1;
        }

        try {
            Branch::where('id', $id)->update($updateBranch);
            
            //Save who updated the user
            $record = new Record();
            $record['user_id'] = $user->id;
            $record['comments'] = 'Actualización de Ramo';
            $record['model'] = 'branch';
            $record['model_id'] = $id;
        
            $record->save();
            
            return redirect('admin/branches');
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteformbranch($id){

        // $branch = Branch::findOrFail($id);
        // return view('admin.deletebranch', compact('branch'));
    }

    public function destroybranch($id) {
        // $branch = Branch::findOrFail($id);
        // $branch->delete();
        
        // return redirect('admin/branches');
    }

    public function showiusers() {
        $iusers = Iuser::orderby('name', 'asc')->get();
        return view('admin.iusers', compact('iusers'));
    }

    public function editiuser($id) {
        $iuser = Iuser::findOrFail($id);
        $encodedimages = $iuser->image_path;
        $images = json_decode($encodedimages, true);

        return view('admin.editiuser', [ 'iuser' => $iuser, 'images' => $images ]);
    }

    public function updateiuser(Request $request, $id) {
        
        //
    }

    public function showbs() {
        $bss = BStatus::orderby('name', 'asc')->get();
        return view('admin.bs', compact('bss'));
    }

    public function createbs() {
        return view('admin.createbs');
    }

    public function storebs(Request $request) {

        $user = auth()->user();

        $this->validate(
            $request,
            array('name' => 'required'),
            array(
                'required' => 'El Campo :attribute es Obligatorio.',
                'name.required' => 'El :attribute es Obligatorio.'
            ),
            array('name' => 'Nombre')
        );

        try {
            $bstatus = BStatus::create($request->all());

            $record = new Record();
            $record['user_id'] = $user->id;
            $record['comments'] = 'Creación de Estado del Negocio';
            $record['model'] = 'bstatus';
            $record['model_id'] = $bstatus->id;
    
            $record->save();

            return redirect('admin/bs');

        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function editbs($id) {

        $bs = BStatus::findOrFail($id);
        return view('admin.editbs', compact('bs'));

    }

    public function updatebs(Request $request, $id) {

        $user = auth()->user();

        $this->validate(
            $request,
            array('name' => 'required'),
            array(
                'required' => 'El Campo :attribute es Obligatorio.',
                'name.required' => 'El Nombre es Obligatorio.'
            )
        );

        try {
            $bs = BStatus::findOrFail($id);
            $bs->update($request->all());

            //Save who updated the user
            $record = new Record();
            $record['user_id'] = $user->id;
            $record['comments'] = 'Actualización de Estado del Negocio';
            $record['model'] = 'bstatus';
            $record['model_id'] = $bs->id;
        
            $record->save();

            return redirect('admin/bs');
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function showresponsibles() {
        $responsibles = Responsible::orderby('name', 'asc')->get();
        return view('admin.responsibles', compact('responsibles'));
    }

    public function createresponsible() {
        return view('admin.createres');
    }
    
    public function storeresponsible(Request $request) {

        $user = auth()->user();

        $this->validate(
            $request,
            array('name' => 'required', 'email' => array('required', 'email')),
            array(
                'required' => 'El Campo :attribute es Obligatorio.',
                'name.required' => 'El Nombre es Obligatorio.',
                'email.required' => 'El Correo es Obligatorio.',
                'email.email' => 'El Email debe ser una Dirección de Correo Electrónico Válida.'
            )
        );

        try{
            $r = Responsible::create($request->all());

            $record = new Record();
            $record['user_id'] = $user->id;
            $record['comments'] = 'Creación de Responsable';
            $record['model'] = 'responsible';
            $record['model_id'] = $r->id;
        
            $record->save();
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

        return redirect('admin/responsibles');
    }

    public function editresponsible($id) {

        $responsible = Responsible::findOrFail($id);
        return view('admin.editres', compact('responsible'));

    }

    public function updateresponsible(Request $request, $id) {

        $user = auth()->user();

        $this->validate(
            $request,
            array('name' => 'required', 'email' => array('required', 'email')),
            array(
                'required' => 'El Campo :attribute es Obligatorio.',
                'name.required' => 'El Nombre es Obligatorio.',
                'email.required' => 'El Correo es Obligatorio.',
                'email.email' => 'El Email debe ser una Dirección de Correo Electrónico Válida.'
            )
        );

        try {
            $responsible = Responsible::findOrFail($id);
            $responsible->update($request->all());

            $record = new Record();
            $record['user_id'] = $user->id;
            $record['comments'] = 'Actualización de Responsable';
            $record['model'] = 'responsible';
            $record['model_id'] = $responsible->id;
        
            $record->save();

            return redirect('admin/responsibles');
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function showiusertypes() {
        $iusertypes = IuserType::all();
        return view('admin.iusertypes', compact('iusertypes'));      
    }

    public function editiusertype($id) {
        $iusertype = IuserType::findOrFail($id);
        return view('admin.editiusertype', compact('iusertype'));
    }

    public function updateiusertype(Request $request, $id) {

        $user = auth()->user();
        
        $this->validate(
            $request,
            array('name' => 'required'),
            array(
                'required' => 'El Campo :attribute es Obligatorio.',
                'name.required' => 'El Nombre es Obligatorio.'
            )
        );

        try {
            $iusertype = IuserType::findOrFail($id);
            $iusertype->update($request->all());

            $record = new Record();
            $record['user_id'] = $user->id;
            $record['comments'] = 'Actualización de Tipo Cliente';
            $record['model'] = 'iusertype';
            $record['model_id'] = $iusertype->id;
        
            $record->save();

            return redirect('admin/iusertypes');
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function createiusertype() {
        return view('admin.createiusertype');
    }
    
    public function storeiusertype(Request $request) {

        $user = auth()->user();

        $this->validate($request,[
            'name'=> 'required'
        ]);

        try{
            $iusertype = IuserType::create($request->all());

            $record = new Record();
            $record['user_id'] = $user->id;
            $record['comments'] = 'Creación de Tipo Cliente';
            $record['model'] = 'iusertype';
            $record['model_id'] = $iusertype->id;
        
            $record->save();
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

        return redirect('admin/iusertypes');
    }

    public function showusers() {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function edituser($id) {
        $user = User::findOrFail($id);
        return view('admin.edituser', compact('user'));
    }

    public function updateuser(Request $request, $id) {

        $user = auth()->user();

        $this->validate(
            $request,
            array('name' => 'required'),
            array(
                'required' => 'El Campo :Nombre es Obligatorio.',
                'name.required' => 'El Nombre es Obligatorio.'
            )
        );

        try {
            $newUser = User::findOrFail($id);
            $newUser->update($request->all());

            //Save who updated the user
            $record = new Record();
            $record['user_id'] = $user->id;
            $record['comments'] = 'Actualización de Usuario';
            $record['model'] = 'user';
            $record['model_id'] = $newUser->id;
            
            $record->save();

            return redirect('admin/users');
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
    
}
