<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function index()
    {
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }
    
    public function cambiarTipoUsuario($id)
    {
        $user = User::findOrFail($id);
        $authUser = auth()->user();

        if ($authUser->tipo !== 'admin' || $authUser->id === $user->id) {
            return back()->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $user->tipo = $user->tipo === 'admin' ? 'cliente' : 'admin';
        $user->save();

        return back()->with('mensaje', 'Tipo de usuario actualizado correctamente.');
    }
    
    public function verTareas($id, Request $request)
    {
        $user = User::with('tareas.proyecto')->findOrFail($id);
    
        $tareas = $user->tareas();
    
        // Aplicar filtros
        if ($request->filled('estado')) {
            $tareas = $tareas->where('estado', $request->estado);
        }
    
        if ($request->filled('prioridad')) {
            $tareas = $tareas->where('prioridad', $request->prioridad);
        }
    
        if ($request->filled('proyecto')) {
            $tareas = $tareas->whereHas('proyecto', function ($query) use ($request) {
                $query->where('nombre', 'like', '%' . $request->proyecto . '%');
            });
        }
    
        $tareas = $tareas->get();
    
        return view('usuarios.tareas', compact('user', 'tareas'));
    }    

}
