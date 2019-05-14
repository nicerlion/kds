<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Item;
use App\Branch;
use App\Iuser;
use App\InsuranceCo;
use App\IuserType;
use App\BStatus;
use App\Responsible;
use App\Record;
use Carbon\Carbon;
use DateTime;

class ItemController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index() {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    public function plate() {
        return view('items.plate');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $branches = Branch::pluck('name', 'id');
        $companies = InsuranceCo::pluck('name', 'id');
        $bstatus = BStatus::pluck('name', 'id');
        $responsibles = Responsible::pluck('name','id');
        $isuerTypes = IuserType::all();
        $isuerTypesEncode = json_encode($isuerTypes);
        $carbon = new Carbon();

        return view('items.create', ['branches' => $branches, 'companies' => $companies, 'isuerTypes' => $isuerTypes, 'bstatus' => $bstatus, 'responsibles' => $responsibles, 'carbon' => $carbon, 'isuerTypesEncode' => $isuerTypesEncode]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $types = IuserType::all();
        $user = auth()->user();

        $messages = array(
            'required' => 'El Campo :attribute es Obligatorio.',
        );

        $validate = array(
            'noitem' => 'required',
            'duedate' => 'required',
        );

        $attributes = array(
            'noitem' => 'No. póliza',
            'duedate' => 'Vencimiento de la Póliza',
        );        
        
        $sarlaf = $request->input('sarlaf');

        if ($sarlaf == null) {
            $sarlafvalue = 0;
        } else {
            $sarlafvalue = 1;
            $validate['sarlafduedate'] = 'required';
            $attributes['sarlafduedate'] = 'Vencimiento de Salaft';
        }

        foreach($types as $t) {
            $validate['userdoctype' .  $t->id . '.*'] = 'required';
            $validate['usernametype' .  $t->id . '.*'] = 'required';
            $attributes['userdoctype' .  $t->id . '.*'] = 'Documento del ' .  $t->name;
            $attributes['usernametype' .  $t->id . '.*'] = 'Nombre del ' .  $t->name;
        }

        $this->validate($request, $validate, $messages, $attributes);

        $iusers_types = array();
        $type1 = 'Individual';
        $type2 = 'Colectiva';
        $typeCounter = 0;
        $userCounter = 0;

        foreach($types as $t) {

            $typeCounter++;

            $iuserName = $request->input('usernametype' .  $t->id);

            foreach($request->input('userdoctype' .  $t->id) as $key => $iuserDoc) {

                if ($iuserDoc) {
                    $iuserData = Iuser::where('document', '=', $iuserDoc)->get();
                    $userCounter++;

                    if (!count($iuserData)) {
                        try {

                            //Save new user on iuser table and iuser_iuser_type pivot table
                            $newIuser = new Iuser;
                            $newIuser['document'] = $iuserDoc;
                            $newIuser['name'] = $iuserName[$key];
                            $newIuser->save();
                            $iuserId = $newIuser->id;

                        } catch (\Exception $e) {
                            return $e->getMessage();
                        }
                    } else {
                        foreach($iuserData as $d) {
                            $iuserId = $d->id;
                        }
                    }

                    $iuser = Iuser::leftjoin('iuser_iuser_type', 'iusers.id', '=', 'iuser_iuser_type.iuser_id')
                    ->where('iuser_iuser_type.iuser_type_id', $t->id)
                    ->where('iusers.document', $iuserDoc)
                    ->select('iusers.*')
                    ->get();

                    if (!count($iuser)) {
                        try {

                            //Save new user on iuser table and iuser_iuser_type pivot table
                            $newUser = new Iuser;
                            $newUser['id'] = $iuserId;
                            $newUser->iusertypes()->attach($t->id);

                            //Save who created the item
                            $record = new Record();
                            $record['user_id'] = $user->id;
                            $record['comments'] = 'Creación de Cliente';
                            $record['model'] = 'iuser';
                            $record['model_id'] = $newUser->id;
                            $record->save();


                        } catch (\Exception $e) {
                            return $e->getMessage();
                        }
                    }

                    $iusers_types[] = array('iuser_id' => $iuserId, 'type_id' => $t->id);
                }
            }
        }

        //Verify if User exists

        $newItem = new Item;
        $newItem['item_number'] = $request->input('noitem');
        $newItem['branch_id'] = $request->input('branches');
        $newItem['bs_id'] = $request->input('bstatus');
        $newItem['insco_id'] = $request->input('companies');
        $newItem['responsible_id'] = $request->input('responsibles');
        $newItem['plate'] = $request->input('plate');
        $ddate = $request->input('duedate');
        $newItem['due_date'] = Carbon::createFromFormat('d/m/Y', $ddate)->format('Y-m-d');
        $newItem['expired'] = false;
        $newItem['sarlaf'] = $sarlafvalue;
        $newItem['sarlaf_expired'] = false;
        $newItem['comments'] = $request->input('comments');
        $newItem['item_type'] = ($typeCounter < $userCounter) ? $type2 : $type1;

        if ($sarlafvalue) {
            $esdd = $request->input('sarlafduedate');
            $newItem['sarlaf_duedate'] = $esdd ? Carbon::createFromFormat('d/m/Y', $esdd)->format('Y-m-d') : null;
        } else {
            $newItem['sarlaf_duedate'] = null;
        }

        if ($request->hasfile('filename')) {

            $data = [];

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

            $newItem['image_path']= json_encode($data);
        }

        try {
            $newItem->save();            
            $newItem->iusers()->sync($iusers_types);

            //Save the user who made the update
            $record = new Record();
            $record['user_id'] = $user->id;
            $record['comments'] = 'Creación de póliza';
            $record['model'] = 'item';
            $record['model_id'] = $newItem->id;
            $record->save();

        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return redirect('items');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        
        $item = Item::findOrFail($id);
        $branch = Branch::findOrFail($item->branch_id);
        $insuranceco = InsuranceCo::findOrFail($item->insco_id);
        $bstatus = BStatus::findOrFail($item->bs_id);
        $responsible = Responsible::findOrFail($item->responsible_id);
        $iusers = Item::select('iusers.*', 'iuser_types.name as type_name')
        ->leftjoin('item_iuser', 'items.id', '=', 'item_iuser.item_id')
        ->leftjoin('iusers', 'iusers.id', '=', 'item_iuser.iuser_id')
        ->leftjoin('iuser_types', 'iuser_types.id', '=', 'item_iuser.type_id')
        ->where('items.id', $id)
        ->orderBy('iuser_types.id', 'asc')
        ->get();

        $images = json_decode($item->image_path, true);
        $files = [];

        if($images){
            foreach( $images as $image) {
                $imagepath = $image['fullpath'];
                $files[] = array(
                    'path' => Storage::disk('s3')->temporaryUrl($imagepath, now()->addMinutes(10)),
                    'type' => $image['type'],
                    'oriname' => (isset($image['oriname'])) ? $image['oriname'] : $image['name'],
                );
            }
        }
        
        return view('items.itemdetail',['item' => $item, 'iusers' => $iusers, 'branch' => $branch, 'insuranceco' => $insuranceco, 'bstatus' => $bstatus, 'responsible' => $responsible, 'files' => $files]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        
        $item = Item::findOrFail($id);
        $currentbranch = Branch::findOrFail($item->branch_id);
        $currentcompany = InsuranceCo::findOrFail($item->insco_id);
        $currentresponsible = Responsible::findOrFail($item->responsible_id); 
        $branch = Branch::pluck('name', 'id');
        $company = InsuranceCo::pluck('name', 'id');
        $bstatus = BStatus::pluck('name', 'id');
        $responsible = Responsible::pluck('name', 'id');
        
        $images = json_decode($item->image_path, true);
        $files = [];

        if($images){
            foreach( $images as $image) {
                $imagepath = $image['fullpath'];
                $files[] = array(
                    'path' => Storage::disk('s3')->temporaryUrl($imagepath, now()->addMinutes(10)),
                    'type' => $image['type'],
                    'oriname' => (isset($image['oriname'])) ? $image['oriname'] : $image['name'],
                    'name' => str_replace('.', '', $image['name']),
                );
            }
        }

        return view('items.edit', ['branch' => $branch, 'company' => $company, 'item' => $item, 'responsible' => $responsible, 'bstatus' => $bstatus, 'currentbranch' => $currentbranch, 'currentcompany' => $currentcompany, 'currentresponsible' => $currentresponsible, 'files' => $files]);
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
        $messages = array(
            'required' => 'El Campo :attribute es Obligatorio.',
        );

        $validate = array(
            'item_number' => 'required',
            'due_date' => 'required',
        );

        $attributes = array(
            'item_number' => 'No. póliza',
            'due_date' => 'Vencimiento de la Póliza',
        );        

        $sarlaf = $request->input('sarlaf');

        if ($sarlaf == null) {
            $sarlafvalue = 0;
        } else {
            $sarlafvalue = 1;
            $validate['sarlaf_duedate'] = 'required';
            $attributes['sarlaf_duedate'] = 'Vencimiento de Salaft';
        }

        $this->validate($request, $validate, $messages, $attributes);

        $newItem = [];
        $newItem['item_number'] = $request->input('item_number');
        $newItem['branch_id'] = $request->input('branch_id');
        $newItem['bs_id'] = $request->input('bs_id');
        $newItem['insco_id'] = $request->input('insco_id');
        $newItem['responsible_id'] = $request->input('responsible_id');
        $newItem['plate'] = $request->input('plate');
        $edd = $request->input('due_date');
        $newItem['due_date'] = Carbon::createFromFormat('d/m/Y', $edd)->format('Y-m-d');
        $newItem['expired'] = false;
        $newItem['sarlaf'] = $sarlafvalue;
        $newItem['sarlaf_expired'] = false;
        $newItem['comments'] = $request->input('comments');

        if ($sarlafvalue) {
            $esdd = $request->input('sarlaf_duedate');
            $newItem['sarlaf_duedate'] = $esdd ? Carbon::createFromFormat('d/m/Y', $esdd)->format('Y-m-d') : null;
        } else {
            $newItem['sarlaf_duedate'] = null;
        }

        $currentItem = Item::findOrFail($id);

        $data = array();
        if($currentItem->image_path){
            if (auth()->user()->isAdmin()){
                $delete = array();
                $files = json_decode($currentItem->image_path, true);

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
                $data = json_decode($currentItem->image_path, true);
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
        
        $newItem['image_path']= json_encode($data);
        $newItem['item_type'] = $currentItem->item_type;

        try {
            Item::where('id', $id)->update($newItem);
            
            //Save the user who made the update
            $record = new Record();
            $record['user_id'] = $user->id;
            $record['comments'] = 'Actualización a póliza';
            $record['model'] = 'item';
            $record['model_id'] = $id;
            $record->save();
            
            return redirect()->route('itemdetail', ['id' => $id]);
            
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
    public function destroy($id) {
        //
    }

    public function testquery() {

        // $date = new DateTime();
        // $date->modify('+ 30 days');

        // $more_date = $date->format('Y-m-d H:i:s');

        // $sarlaftUsers = Iuser::where('sarlaf_duedate', '>=', Carbon::now())
        // ->where('sarlaf_duedate','>',$more_date)
        // ->select('iusers.*')
        // ->get();

        // foreach ($sarlaftUsers as $u){
        //     echo $u->name .'<br>';
        // }

        $iusers = Branch::select('iusers.*', 'item_iuser.type_id', 'branches.id as branche_id', 'branches.name as branche_name')
            ->leftjoin('items', 'branches.id', '=', 'items.branch_id')
            ->leftjoin('item_iuser', 'items.id', '=', 'item_iuser.item_id')
            ->leftjoin('iusers', 'item_iuser.iuser_id', '=', 'iusers.id')
            ->where('branches.sarlaf', '=', 1)
            ->where('iusers.sarlaf', '=', 0)
            ->orWhere('iusers.sarlaf_duedate', '<', now())
            ->orderBy('iusers.id', 'desc')
            ->get();

        echo $iusers;
        
    }
}
