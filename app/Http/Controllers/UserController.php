<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index() {
        $usuarios = User::with('role')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create() {
        //Obtener los roles para el combo seleccionable
        $roles = Role::all();
        //delvover la vista de creacion de usarios con los roles disponibles
        return view('usuarios.create', compact('roles'));
    }

    // Método para almacenar un nuevo usuario en la base de datos
    public function store(Request $request) {
        // Validación de los datos recibidos

        // dd($request->all());

        $request->validate([
            'nombre' => 'required|string|max:255', // Nombres requeridos
            'apellido' => 'required|string|max:255', // Apellidos requeridos
            'telefono' => 'required|string|max:15', // Teléfono requerido
            'email' => 'required|string|email|max:255|unique:users', // Email único requerido
            'rol_id' => 'required|integer', // Rol requerido
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Foto opcional
        ]);
        // Almacenar la foto si fue proporcionada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos','public'); // Almacena la foto en el sistema de archivos
        }
        // Creación del nuevo usuario con los datos validados
        User::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'rol_id' => $request->rol_id,
            'password' => Hash::make('password'), // Se establece una contraseña predeterminada
            'foto' => $fotoPath,
        ]);
        // Redirigir a la lista de usuarios con un mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', 'Registro Creado Correctamente');
    }

    public function edit($id) {
        $usuario = User::findOrFail($id);
        // $usuario = User::findOfFail($id);
        $roles = Role::all();

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nombre' => 'required|string|max:255', // Nombres requeridos
            'apellido' => 'required|string|max:255', // Apellidos requeridos
            'telefono' => 'required|string|max:15', // Teléfono requerido
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'rol_id' => 'required|integer', // Rol requerido
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Foto opcional
        ]);

        $usuario = User::findOrFail($id);

        if($request->hasFile('foto')){
            $fotoPath = $request->file('foto')->store('foto', 'public');
            $usuario->foto =$fotoPath;
        }

        $usuario->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'rol_id' => $request->rol_id,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Registro Creado Correctamente');
    }

    public function destroy($id) {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Registro Eliminado Correctamente');
    }

}
