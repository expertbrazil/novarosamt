<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('admin.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];

        $messages = [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser um endereço válido.',
            'email.unique' => 'Este email já está em uso.',
        ];

        // Se o usuário está tentando alterar a senha
        if ($request->filled('password')) {
            $rules['current_password'] = 'required';
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
            $messages['current_password.required'] = 'A senha atual é obrigatória para alterar a senha.';
            $messages['password.required'] = 'A nova senha é obrigatória.';
            $messages['password.confirmed'] = 'A confirmação da senha não confere.';
        }

        $validated = $request->validate($rules, $messages);

        // Validar senha atual se estiver tentando alterar
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'A senha atual está incorreta.'
                ])->withInput();
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.profile.show')
            ->with('success', 'Perfil atualizado com sucesso!');
    }
}

