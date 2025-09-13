<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
   
    public function index(Request $request)
     {
        $query = Supplier::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        $data = $query->orderBy('nama')->paginate(10);

        // Kirim ke view
        return view('content.supplier.index', compact('data'));
    }

    // Menyimpan data supplier baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'perusahaan' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:255',
        ]);

        Supplier::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'perusahaan' => $request->perusahaan,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->back()->with('success', 'Supplier berhasil ditambahkan');
    }

    // Menghapus data supplier
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('admin.supplier.index')->with('success', 'Supplier berhasil dihapus');
    }

    // Menampilkan form edit (tidak digunakan jika modal inline di halaman index)
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('content.supplier.edit', compact('supplier'));
    }

    // Mengupdate data supplier
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'perusahaan' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:255',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'perusahaan' => $request->perusahaan,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->route('admin.supplier.index')->with('success', 'Supplier berhasil diupdate');
    }
}
