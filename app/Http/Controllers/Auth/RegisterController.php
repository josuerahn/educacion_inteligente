<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\SocialProfile;
use App\Models\Tutoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
   public function showRegistrationForm()
{
    $cursos = Tutoria::all();
    return view('auth.register', compact('cursos'));
}

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => [ 'required', 'string','confirmed', 'min:6',],
            'fecha_nacimiento' => 'required|date',
            'profile_photo' => 'nullable|image|max:5048',
        ], [
            'email.unique' => 'Este correo ya existe.',
            'dni.unique' => 'Este DNI ya existe.',
           
        ]);





        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'profile_photo' => $photoPath,
                'fecha_nacimiento' => $request->fecha_nacimiento,
               
                    // asigna rol 'Alumno' si existe, si no usar 3 por defecto
                    'role_id' => Role::where('name', 'Alumno')->value('id') ?? 3,
            ]);

           

            DB::commit();
            // Si quieres loguear automáticamente al usuario tras el registro:
            // Auth::login($user);

            return redirect()->route('login')->with('success', 'Registro exitoso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error en el registro: ' . $e->getMessage()])->withInput();
        }
    }
}
