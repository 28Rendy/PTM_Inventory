<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    // Menampilkan semua data pengeluaran
    public function index()
    {
        $data = Pengeluaran::latest()->paginate(10);
        return view('content.pengeluaran.index', compact('data'));
    }

    // Menyimpan data pengeluaran baru
    public function store(Request $request)
    {
        $request->validate([
            'deskripsi' => 'required|string|max:255',
            'nominal' => 'required|integer|min:0|max:99999999999', // maksimal 11 digit
        ]);

        Pengeluaran::create([
            'deskripsi' => $request->deskripsi,
            'nominal' => $request->nominal,
        ]);

        return redirect()->back()->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    // Menghapus data pengeluaran
    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->delete();

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus');
    }

    // Menampilkan form edit pengeluaran
    public function edit($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        return view('content.pengeluaran.edit', compact('pengeluaran'));
    }

    // Mengupdate data pengeluaran
    public function update(Request $request, $id)
    {
        $request->validate([
            'deskripsi' => 'required|string|max:255',
            'nominal' => 'required|integer|min:0|max:99999999999', // maksimal 11 digit
        ]);

        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->update([
            'deskripsi' => $request->deskripsi,
            'nominal' => $request->nominal,
        ]);

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Pengeluaran berhasil diupdate');
    }
}
