<?php

namespace App\Http\Controllers\Admin\Rol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filtro por nombre de rol
        $name = $request->search;

        $roles = Role::where('name', 'LIKE', "%$name%")->orderBy('id', 'desc')->get();

        return response()->json([
            'roles' => $roles->map(function($rol) {
                return [
                    "id" => $rol->id,
                    "name" => $rol->name,
                    "permissions" => $rol->permissions,
                    "created_at" => $rol->created_at->format('Y-m-d H:i:s'),
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de datos
        $is_role = Role::where('name', $request->name)->first();

        if ($is_role) {
            return response()->json([
                'message' => 403,
                'message_text' => 'El rol ya existe',
            ]);
        }

        $role = Role::create([
            'guard_name' => 'api',
            'name' => $request->name,
        ]);

        //["register_rol", "edit_rol", "register_paciente"];
        foreach ($request->permissions as $key => $permission) {
            $role->givePermissionTo($permission);
        }

        return response()->json ([
            'message' => 200,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validación de datos
        $is_role = Role::where("id", "<>", $id)->where('name', $request->name)->first();

        if ($is_role) {
            return response()->json([
                'message' => 403,
                'message_text' => 'El rol ya existe',
            ]);
        }

        $role = Role::findOrFail($id);

        $role->update($request->all());

        //["register_rol", "edit_rol", "register_paciente"];
        $role->syncPermissions($request->permissions);

        return response()->json ([
            'message' => 200,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json ([
            'message' => 200,
        ]);
    }
}
