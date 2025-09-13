<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('kategori');

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_barang', 'like', '%' . $request->search . '%')
                ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
        }

        $barang = $query->orderBy('nama_barang')->paginate(10);
        $kategori = Kategori::all();

        return view('content.barang.index', compact('barang', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan' => 'required|string|max:255',
            'stok' => 'required|integer',
            'harga_beli' => 'required',
            'harga_jual' => 'nullable',
        ]);

        // Bersihkan format ribuan dari input harga
        $hargaBeli = intval(str_replace('.', '', $request->harga_beli));
        $hargaJual = $request->harga_jual ? intval(str_replace('.', '', $request->harga_jual)) : null;

           // ✅ Validasi harga jual
            if (!is_null($hargaJual) && $hargaJual < $hargaBeli) {
                return back()
                    ->withErrors(['harga_jual' => 'Harga Jual harus lebih tinggi dari Harga Beli.'])
                    ->withInput()
                    ->with('modal', 'addRowModal'); 
    }
        
        // Generate kode barang otomatis
        $lastId = Barang::max('id') ?? 0;
        $newCode = 'BRG-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        Barang::create([
            'kode_barang' => $newCode,
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'satuan' => $request->satuan,
            'stok' => $request->stok,
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaJual,
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategori = Kategori::all();

        return view('content.barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:255|unique:barang,kode_barang,' . $id,
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan' => 'required|string|max:255',
            'stok' => 'required|integer',
            'harga_beli' => 'required',
            'harga_jual' => 'nullable',
        ]);

        $barang = Barang::findOrFail($id);

        // Bersihkan format ribuan dari input harga
        $hargaBeli = intval(str_replace('.', '', $request->harga_beli));
        $hargaJual = $request->harga_jual ? intval(str_replace('.', '', $request->harga_jual)) : null;
        // ✅ Validasi harga jual
                    if (!is_null($hargaJual) && $hargaJual < $hargaBeli) {
                        return back()
                            ->withErrors(['harga_jual' => 'Harga Jual harus lebih tinggi dari Harga Beli.'])
                            ->withInput()
                            ->with('modal', 'addRowModal'); 
            }
        $barang->update([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'satuan' => $request->satuan,
            'stok' => $request->stok,
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaJual,
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
