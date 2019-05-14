<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Iuser;
use App\Branch;
use App\InsuranceCo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        
        $branches = Branch::all()->take(10);
        $companies = InsuranceCo::all()->take(10);
        $iusers = Iuser::all()->take(10)->sortBy('name');
        $items = Item::join('item_iuser', 'items.id', '=', 'item_iuser.item_id')
                    ->join('iusers', 'item_iuser.iuser_id', '=', 'iusers.id')
                    ->select('iusers.*', 'items.*')
                    ->distinct()->get();
        
        return view('home',['items' => $items, 'branches' => $branches, 'iusers' => $iusers, 'companies' => $companies]);
    }
}
