<?php

    namespace App\Http\Controllers;


    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;

    class ProfilController extends Controller
    {
        public function show()
        {
            $user = Auth::user();
            
            return view('content.profile.show', compact('user'));
        }

        // Form edit profil
        public function edit()
        {
            $user = Auth::user();
            return view('content.profile.edit', compact('user'));
        }

        // Simpan update profil
        public function update(Request $request)
        {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
            ]);

            $user = Auth::user();
            $user->name = $request->name;
            $user->email = $request->email;
            // Simpan foto jika ada
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/foto'), $filename);
                $user->foto = 'uploads/foto/' . $filename;
            }
            $user->save();

            return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
        }

        // Form ganti password
        public function changePassword()
        {
            return view('content.profile.edit');
        }

        // Proses update password
        public function updatePassword(Request $request)
        {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ]);

            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama salah']);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->route('profile')->with('success', 'Password berhasil diubah.');
        }
    }