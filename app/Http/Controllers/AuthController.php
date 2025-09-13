<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAuthRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function verify(UserAuthRequest $request) : RedirectResponse
    {
        $data = $request->validated();

        if(Auth::guard('admin')->attempt(['email'=>$data['email'],'password'=>$data['password'],'role'=>'admin'])){
            $request->session()->regenerate();
            return redirect()->intended('/admin/home');
        }else if (Auth::guard('kasir')->attempt(['email'=>$data['email'],'password'=>$data['password'],'role'=>'kasir'])){
            $request->session()->regenerate();
            return redirect()->intended('/kasir/home');
        }else if (Auth::guard('pimpinan')->attempt(['email'=>$data['email'],'password'=>$data['password'],'role'=>'pimpinan'])){
            $request->session()->regenerate();
            return redirect()->intended('/pimpinan/home');
        }else{
            return redirect(route('login'))->with('error','email dan password salah');
        }
    }

    public function logout():RedirectResponse
    {
        if(Auth::guard('admin')->check()){
            Auth::guard('admin')->logout();
        }else if(Auth::guard('kasir')->check()){
            Auth::guard('kasir')->logout();
        }else if(Auth::guard('pimpinan')->check()){
            Auth::guard('pimpinan')->logout();
        }
        return redirect(route('login'));
    }
}

