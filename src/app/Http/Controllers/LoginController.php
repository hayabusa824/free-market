<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function index() {
        return view('auth.login');
    }

    public function store(LoginRequest $request) {
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate(); // セッションを無効化
        $request->session()->regenerateToken(); // CSRF トークンを再生成

        return redirect('/');
    }

}
