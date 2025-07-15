<?php

namespace App\Http\Controllers;
use DB;
use Hash;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Gate::allows('user-list')) {
             abort(403, 'You do not have permissions');
        }

        $users = User::latest()->paginate(10);
        return view('backend.user.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('user-create')) {
             abort(403, 'You do not have permissions');
        }

        $roles = Role::pluck('name','name')->all();
        return view('backend.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'roles' => 'required|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        $user->syncRoles($request->roles);
        return redirect()->route('users.index')->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!Gate::allows('user-edit')) {
             abort(403, 'You do not have permissions');
        }

        $user = User::findOrFail($id);
        $roles = Role::pluck('name','name')->all();
        $userRoles = $user->roles->pluck('name','name')->all();
        return view('backend.user.edit', compact('user','roles', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed',
            'roles' => 'required'
        ]);

        $user = User::findOrFail($id);
        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = $input['password'];
        }else{
            $input = Arr::except($input,array('password'));
        }
        $user->update($input);
        $user->syncRoles($request->roles);
        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Gate::allows('user-delete')) {
             abort(403, 'You do not have permissions');
        }

        User::find($id)->delete();
        return redirect()->route('users.index')
                ->with('success','User deleted successfully');
    }
}
