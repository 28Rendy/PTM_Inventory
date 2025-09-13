<?php

namespace App\Exports;

use App\Models\BarangMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BarangMasukExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithTitle
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan = null, $tahun = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $query = BarangMasuk::with(['barang', 'supplier'])
            ->select('barang_id', 'supplier_id', 'stok_masuk', 'tanggal_masuk', 'keterangan');

        if ($this->bulan && $this->tahun) {
            $query->whereMonth('tanggal_masuk', $this->bulan)
                  ->whereYear('tanggal_masuk', $this->tahun);
        }

        return $query->get()->map(function ($item) {
            return [
                'kode_barang'     => $item->barang->kode_barang ?? '-',
                'nama_barang'     => $item->barang->nama_barang ?? '-',
                'supplier'        => $item->supplier->nama ?? '-',
                'jumlah_masuk'    => $item->stok_masuk,
                'tanggal_masuk'   => date('d-m-Y', strtotime($item->tanggal_masuk)),
                'keterangan'      => $item->keterangan,
            ];
        });
    }

    public function headings(): array
    {
        return ['Kode Barang', 'Nama Barang', 'Supplier', 'Jumlah Masuk', 'Tanggal Masuk', 'Keterangan'];
    }

    public function title(): string
    {
        return 'Laporan Barang Masuk';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Tambahkan judul
                $sheet->insertNewRowBefore(1, 2);
                $sheet->setCellValue('A1', 'LAPORAN BARANG MASUK');

                // Gabungkan sel judul
                $sheet->mergeCells('A1:F1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Tambahkan border pada seluruh tabel
                $rowCount = $sheet->getHighestRow();
                $columnCount = $sheet->getHighestColumn();

                $sheet->getStyle('A3:' . $columnCount . $rowCount)
                      ->getBorders()
                      ->getAllBorders()
                      ->setBorderStyle(Border::BORDER_THIN);
            }
        ];
    }
}
