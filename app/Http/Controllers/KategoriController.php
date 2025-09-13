<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        // Ambil query kategori dan filter jika ada pencarian
        $query = Kategori::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_kategori', 'like', '%' . $request->search . '%');
        }

        // Ambil data kategori dengan paginate
        $data = $query->orderBy('nama_kategori')->paginate(10);

        // Kirim ke view
        return view('content.kategori.index', compact('data'));
    }
 

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_kategori' => 'required|string|max:255',
            ]);

            Kategori::create([
                'nama_kategori' => $request->nama_kategori,
            ]);

            return redirect()->back()->with('success', 'Kategori berhasil ditambahkan');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    // Menghapus user
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.kategori.index', compact('kategori'));
    }

    // Fungsi untuk update data user
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        // Validasi input jika diperlukan
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        // Update data user
        $kategori->nama_kategori = $request->nama_kategori;




        $kategori->save();

        return redirect()->route('admin.kategori.index')->with('success', 'kategori berhasil diupdate');
    }

}
