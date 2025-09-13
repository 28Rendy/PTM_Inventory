<?php

namespace App\Exports;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPenjualanExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected $tahun, $bulan;

    public function __construct($tahun, $bulan)
    {
        $this->tahun = (int) $tahun;
        $this->bulan = is_numeric($bulan) ? (int) $bulan : null;
    }

    public function array(): array
    {
        $query = Penjualan::with(['detailPenjualan.barang', 'user'])
            ->whereYear('tanggal', $this->tahun);

        if (!is_null($this->bulan)) {
            $query->whereMonth('tanggal', $this->bulan);
        }

        $penjualan = $query->get();
        $penjualanIds = $penjualan->pluck('id');

        // Hitung ringkasan
        $totalTransaksi      = $penjualan->count();
        $jumlahBarangTerjual = DetailPenjualan::whereIn('penjualan_id', $penjualanIds)->sum('jumlah');
        $totalModal          = DetailPenjualan::whereIn('penjualan_id', $penjualanIds)
            ->join('barang', 'detail_penjualan.barang_id', '=', 'barang.id')
            ->sum(DB::raw('detail_penjualan.jumlah * barang.harga_beli'));

        $totalPenjualan = DetailPenjualan::whereIn('penjualan_id', $penjualanIds)
            ->sum(DB::raw('jumlah * harga'));

        $pendapatan = $totalPenjualan - $totalModal;

        $data = [];

        // Baris ringkasan (tanpa border)
        $data[] = ['Total Transaksi', $totalTransaksi];
        $data[] = ['Total Modal', 'Rp ' . number_format($totalModal, 0, ',', '.')];
        $data[] = ['Total Penjualan', 'Rp ' . number_format($totalPenjualan, 0, ',', '.')];
        $data[] = ['Pendapatan', 'Rp ' . number_format($pendapatan, 0, ',', '.')];

        $data[] = []; // Baris kosong sebelum tabel detail

        // Header detail transaksi
        $data[] = [
            'Tanggal',
            'Jam',
            'Faktur',
            'User',
            'Total Modal',
            'Total Penjualan',
            'Pendapatan'
        ];

        // Data transaksi
        foreach ($penjualan as $p) {
            $modal = $p->detailPenjualan->sum(fn($d) => $d->jumlah * $d->barang->harga_beli);
            $penjualanHarga = $p->detailPenjualan->sum(fn($d) => $d->jumlah * $d->harga);
            $laba = $penjualanHarga - $modal;

            $data[] = [
                date('d-m-Y', strtotime($p->tanggal)),
                date('H:i', strtotime($p->tanggal)),
                $p->kode_penjualan,
                $p->user->name ?? '-',
                'Rp ' . number_format($modal, 0, ',', '.'),
                'Rp ' . number_format($penjualanHarga, 0, ',', '.'),
                'Rp ' . number_format($laba, 0, ',', '.'),
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        $judul = 'LAPORAN PENJUALAN TAHUN ' . $this->tahun;
        if ($this->bulan) {
            $judul .= ' - BULAN ' . strtoupper(date('F', mktime(0, 0, 0, $this->bulan, 1)));
        }

        return [
            [$judul] // Merge A1:G1
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $allData = $this->array();
        $totalRows = count($allData) + 1; // +1 karena judul

        $startDetailRow = 6; // Baris detail mulai dari baris ke-7 (setelah ringkasan dan header)
        $range = 'A' . $startDetailRow . ':G' . $totalRows;

        // Border hanya untuk detail
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => 'center',
            ],
        ]);

        // Judul di A1
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->mergeCells('A1:G1');

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18, // Tanggal
            'B' => 18,  // Jam
            'C' => 18, // Faktur
            'D' => 15, // User
            'E' => 18, // Total Modal
            'F' => 18, // Total Penjualan
            'G' => 18, // Pendapatan
        ];
    }
}
