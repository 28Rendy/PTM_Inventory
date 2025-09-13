<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data = Users::all();
        return view('content.user.index', compact('data'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,kasir',
            'password' => 'required|min:6',
        ]);

        Users::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan');

    }
    // Menghapus user
    public function destroy($id)
    {
        $user = Users::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus');
    }
    public function edit($id)
    {
        $user = Users::findOrFail($id);
        return view('admin.user.index', compact('user'));
    }

    // Fungsi untuk update data user
    public function update(Request $request, $id)
    {
        $user = Users::findOrFail($id);

        // Validasi input jika diperlukan
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string',
            'password' => 'nullable|min:6',
        ]);

        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Jika password diisi, update password
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('admin.user.index')->with('success', 'User berhasil diupdate');
    }



}
