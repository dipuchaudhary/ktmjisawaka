<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Gate::allows('role-list')) {
             abort(403, 'You do not have permissions');
        }

        $roles = Role::latest()->paginate(10);
        return view('backend.role.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('role-create')) {
             abort(403, 'You do not have permissions');
        }
        $permissions = Permission::all();
        return view('backend.role.create',compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required',
        ]);
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permissions'));
        return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!Gate::allows('role-edit')) {
             abort(403, 'You do not have permissions');
        }

        $role = Role::findOrFail($id);
        $permissions = Permission::get();
        $rolePermissions = $role->permissions()->pluck('name')->toArray();
        return view('backend.role.edit',compact('role','permissions','rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
        ]);

        $role->name = $request->name;
        $role->save();

        $role->syncPermissions($request->input('permissions'));

        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if (!Gate::allows('role-delete')) {
             abort(403, 'You do not have permissions');
        }

        $role->delete();
        return redirect()->route('roles.index')
                ->with('success','Role deleted successfully');
    }
}
