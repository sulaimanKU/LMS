<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController
{
    public function store(Request $request)
    {
        $request->validate([
           'name' => 'required|unique:permissions,name',
        ]);

        Permission::create([

            'name' => $request->name

        ]);
         return redirect()->back()->with('status','Permission Created Successfully');
    }

    public function updateView($id)
    {
        $permission = Permission::findorFail($id);
        return view('layouts.permissions.editpermission' ,compact('permission'));
    }

    public function update (Request $request,$id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        $permission = Permission::findorFail($id);

        $permission->update([
            'name' => $request->name
        ]);

        return redirect()->route('admin.role')->with('status','Permissions Updated Successfully');
    }

    public function destroy ($permissionId)
    {
        $permission = Permission::find($permissionId);
        $permission->delete();

        return redirect()->back()->with('status','Permission Deleted successfully');
    }

}
