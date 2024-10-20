<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\SingleRoleResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        return response()->json([
            'data' => [
                'roles' => RoleResource::collection($roles)
            ]
        ])->setStatusCode(200);
    }


    public function show(Role $role)
    {
        return response()->json([
           'data' => [
               'role' => new SingleRoleResource($role)
           ]
        ])->setStatusCode(200);
    }

    public function store(NewRoleRequest $request)
    {
        /**
         * @var Role $role
         */


        $role = Role::query()->create([
            'title' => $request->get('title')
        ]);

        if ($request->filled('permissions')) {

            $permissions = Permission::query()
                ->whereIn('title', $request->get('permissions'))->get();


            $role->permissions()->attach($permissions);

        }

        return response()->json([
            'data' => [
                'role' => new SingleRoleResource($role)
            ]
        ])->setStatusCode(201);
    }

    public function update(Role $role, UpdateRoleRequest $request)
    {
        $roleExists = Role::query()
            ->where('title', $request->get('title'))
            ->where('id','!=',$role->id)
            ->exists();

        if ($roleExists){
            return response()->json([
                'data' => [
                    'message' => 'title already been taken'
                ]
            ])->setStatusCode(400);
        }

        $role->update([
           'title' => $request->get('title',$role->title)
        ]);

        $permissions = Permission::query()
            ->whereIn('title', $request->get('permissions'))
            ->get();

        $role->permissions()->sync($permissions);


        return response()->json([
            'data' => [
                'role' => new SingleRoleResource($role)
            ]
        ])->setStatusCode(200);
    }

    public function destroy(Role $role)
    {
        $RoleHasUsers = $role->users()->count();

        if ($RoleHasUsers) {
            return response()->json([
                'data' => [
                    'message' => 'role has many users'
                ]
            ])->setStatusCode(400);
        }

        $role->permissions()->detach();

        $role->delete();

        return response()->json([
            'data' => [
                'message' => 'role successfully deleted'
            ]
        ])->setStatusCode(200);

    }
}
