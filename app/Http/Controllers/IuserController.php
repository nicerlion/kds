<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Iuser;
use App\Record;
use App\Branch;
use App\IuserType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;


class IuserController extends Controller
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
        $iusers = Iuser::all();
        return view('iusers.index',['iusers' => $iusers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return view('iusers.create');
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
        $iuser = Iuser::findOrFail($id);

        $images = json_decode($iuser->image_path, true);

        $files = [];

        if($images){
            foreach( $images as $image) {
                $imagepath = $image['fullpath'];
                $files[] = array(
                    'path' => Storage::disk('s3')->temporaryUrl($imagepath, now()->addMinutes(10)),
                    'type' => $image['type'],
                    'name' => str_replace('.', '', $image['name']),
                    'oriname' => (isset($image['oriname'])) ? $image['oriname'] : $image['name'],
                );
            }
        }

        return view('iusers.edit', [ 'iuser' => $iuser, 'files' => $files ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
        $user = auth()->user();
        $updateUser = [];

        $messages = array(
            'required' => 'El Campo :attribute es Obligatorio.',
        );

        $validate = array(
            'name' => 'required',
            'document' => 'required',
        );

        $attributes = array(
            'name' => 'Nombre',
            'document' => 'Documento',
            'sarlaf_duedate' => 'Vencimiento de Salaft',
        );

        $sarlaf = $request->input('sarlaf');
        if ($sarlaf == null) {
            $updateUser['sarlaf'] = 0;
            $updateUser['sarlaf_duedate'] = null;
        } else {
            $updateUser['sarlaf'] = 1;
            $esdd = $request->input('sarlaf_duedate');
            $validate['sarlaf_duedate'] = 'required';
            $updateUser['sarlaf_duedate'] = $esdd ? Carbon::createFromFormat('d/m/Y', $esdd)->format('Y-m-d') : null;
        }

        $this->validate($request, $validate, $messages, $attributes);

        $updateUser['name'] = $request->input('name');
        $updateUser['document'] = $request->input('document');
        $updateUser['sarlaf_expired'] = false;

        $currentUser = Iuser::findOrFail($id);

        $data = array();
        if($currentUser->image_path){
            if (auth()->user()->isAdmin()){
                $delete = array();
                $files = json_decode($currentUser->image_path, true);

                foreach ($files as $file) {
                    $fname = str_replace('.', '', $file['name']);
                    $checkboxfile = $request->input($fname);
                    if ($checkboxfile != null) {
                        $delete[] = $file['fullpath'];
                    } else {
                        $data[] = $file;
                    }
                }

                if ($delete) {
                    Storage::disk('s3')->delete($delete);
                }
            } else {
                $data = json_decode($currentUser->image_path, true);
            }
        }

        if ($request->hasfile('filename')) {
            
            foreach($request->file('filename') as $key => $image) {
                
                $type = $image->getClientOriginalExtension();
                $filename  = 'kds-file-' . time() . '-' . $key . '.' . $type;
                $name = $image->getClientOriginalName();
                $year = date('Y');
                $month = date('m');
                $day = date('d');
                $path = 'images/'. $year . '/' . $month . '/' . $day . '/';
                $fullpath = $path . $filename;
                $data[] = array('name' => $filename, 'oriname' => $name, 'path' => $path, 'type' => $type, 'fullpath' => $fullpath);

                //Store on AWS
                $storeonaws = $image->storeAs($path, $filename, 's3');
                
            }

        }

        $updateUser['image_path']= json_encode($data);

        try {
            Iuser::where('id', $id)->update($updateUser);

            //Save the user who made the update
            $record = new Record();
            $record['user_id'] = $user->id;
            $record['comments'] = 'Actualización de Usuario';
            $record['model'] = 'cliente';
            $record['model_id'] = $id;
            $record->save();
            
            return redirect('iusers?id=' . $updateUser['document']);
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $iuser = Iuser::findOrFail($id);
        // $iuser->delete();
        
        // return redirect('iusers');
    }

    public function deleteform($id){

        // $iuser = Iuser::findOrFail($id);
        // return view('iusers.delete', compact('iuser'));
    }

    public function showexpiredsarlaft() {
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

        $resuser = array ();

        foreach ($iusers as $key => $iuser) {
            
            if (isset($resuser[$iuser->id])) {
                if (!(isset($userdata[$iuser->id]['types'][$iuser->type_id]))) {
                    $resuser[$iuser->id][2] = $resuser[$iuser->id][2] . ' | ' . $types[$iuser->type_id];
                    $userdata[$iuser->id]['types'][$iuser->type_id] = '';
                }
                if (!(isset($userdata[$iuser->id]['branches'][$iuser->branche_id]))) {
                    $resuser[$iuser->id][3] = $resuser[$iuser->id][3] . ' | ' . $iuser->branche_name;
                    $userdata[$iuser->id]['branches'][$iuser->branche_id] = '';
                }
            } else {
                $resuser[$iuser->id] = array(
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


        return view('iusers.expiredsarlaft', compact('resuser'));
    }

    public function expirationreport(){
        $date = date('d-m-Y');
        return Excel::download(new UsersExport, 'reporte-sarlaft-vencidos-' . $date . '.xlsx');
    }
    
}
