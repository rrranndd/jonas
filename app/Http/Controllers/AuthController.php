<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'Username atau password salah');
        }

        session([
            'admin_logged_in' => true,
            'admin_name' => $admin->username,
        ]);

        return redirect()->route('dashboard')->with('success', 'Login berhasil!');
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}
