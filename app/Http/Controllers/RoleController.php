<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Role::all();
        return response([
            "message" => "Role List",
            "data" => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required'
        ]);

        Role::create([
            'role_name' => $request->role_name
        ]);

        return response([
            "message" => "Role created"
        ], 201);
    } 

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Role::find($id);

        if (is_null($data)) {
            return response([
                "message" => "Role Not Found",
                "data" => [],
            ], 404);
        }
        return response([
            "message" => "Role Detail",
            "data" => $data
        ]);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
