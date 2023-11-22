<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequests\AssignRoleRequest;
use App\Http\Requests\AdminRequests\RemoveRoleRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:admin');
    }
    public function assignRole(AssignRoleRequest $request)
    {
        $validatedData=$request->validated();
        $user = User::find($validatedData->user_id);
        $user->assignRole($validatedData->role);

        return response()->json(['message' => 'Role assigned successfully'], 200);
    }
    public function removeRole(RemoveRoleRequest $request)
    {
        $validatedData=$request->validated();
        $user = User::find($validatedData->user_id);

        $user->removeRole($validatedData->role);

        return response()->json(['message' => 'Role removed successfully'], 200);
    }

}
