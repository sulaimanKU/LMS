<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolesController
{


  public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);
    //  /** @var \App\Models\User $user */
    // $user = Auth::user();

    // Check if user exists and has the permission
    // if (!$user || !$user->can('role-manage')) {
    //     abort(403, 'Permission Denied');
    // }

        Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        return redirect()->back()->with('status', 'Role created successfully');
    }
    public function updateView($roleId)
{
    // 1. Check permission manually if constructor middleware is failing
    // if (!auth()->user()->can('role-manage')) {
    //     abort(403);
    // }

    $role = Role::findOrFail($roleId);

    return view('layouts.editRole.editRole', compact('role'));
}
    public function update(Request $request, $role)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $role = Role::findorFail($role);

        $role->update([
            'name' => $request->name,
        ]);

       if ($request->is('admin/*')) {
        return redirect()->route('admin.role')->with('status', 'Role Updated Successfully');
    }

    return redirect()->route('teacher.role')->with('status', 'Role Updated Successfully');
    }

    public function destroy($id)
    {
        $role = Role::findorFail($id);
        $role->delete();
        return redirect()->back()->with('status', 'Role Deleted Successfuly');
    }
    public function rolesOrpermission()
    {

        $all_roles = Role::all();
        $all_permissions = Permission::all();

        $permissions = Role::with('permissions')->get();


        return view('layouts.rolesORpermissions.index', compact('all_roles', 'all_permissions', 'permissions'));
    }


    public function roleORpermissions(Request $request)
    {

        // dd($request->role_id);
        $role = Role::findorFail($request->role_id);
        $role->syncpermissions($request->permissions);
        return back()->with('status', 'Permissions created for ' . $role->name);
    }

    public function updateRolepermission($id)
    {

        $active_role = Role::findorFail($id);
        $all_permissions = Permission::all();

        return view('layouts.rolesORpermissions.edit_permission_role', compact('active_role', 'all_permissions'));
    }


    public function editRolepermission(Request $request, $id)
    {
        $role = Role::findorFail($id);
        $role->syncpermissions($request->permissions);
        return redirect()->back()->with('status', 'Permissions are Updated Successfully');
    }

    public function deleteRole($id)
    {
        $role = Role::findorFail($id);

        $role->delete();
        return back()->with('status', 'Role deleted successfully!');
    }

    public function roleManageView()
    {
        $users = User::all();
        $all_roles = Role::all();
        $all_permissions = Permission::all();
        return view('layouts.roleManage.roleManage', compact('users', 'all_roles','all_permissions'));
    }

    public function userRoleAssined(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $user->syncRoles($request->role_name);

        return back()->with('success', "Role updated for {$user->name}!");
    }
    public function deleteuserRole($id)
    {

        if (Auth::id() == $id) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user = User::findOrFail($id);


        $user->delete();

        return back()->with('success', 'User has been removed from the system.');
    }
}
