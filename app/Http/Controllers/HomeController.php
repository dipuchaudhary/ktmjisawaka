<?php

namespace App\Http\Controllers;

use App\Models\BankingMudda;
use App\Models\Challani;
use App\Models\MuddaDarta;
use App\Models\Punarabedan;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

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
    public function index()
    {
        $users = User::count();
        $roles = Role::count();
        $mull = MuddaDarta::count();
        $banking = BankingMudda::count();
        $challani = Challani::count();
        $punarabedan = Punarabedan::count();
        return view('backend.dashboard',compact('users','roles','mull','banking','challani','punarabedan'));
    }
}
