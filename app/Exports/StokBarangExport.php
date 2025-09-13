<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StokBarangExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithTitle
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
        $query = Barang::select('kode_barang', 'nama_barang', 'stok', 'satuan', 'harga_beli', 'harga_jual');

        if ($this->bulan && $this->tahun) {
            $query->whereMonth('created_at', $this->bulan)
                  ->whereYear('created_at', $this->tahun);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['Kode Barang', 'Nama Barang', 'Stok', 'Satuan', 'Harga Beli', 'Harga Jual'];
    }

    public function title(): string
    {
        return 'Laporan Stok Barang';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Tambahkan judul
                $sheet->insertNewRowBefore(1, 2); // Sisipkan 2 baris di atas
                $sheet->setCellValue('A1', 'LAPORAN STOK BARANG');

                // Gabungkan sel untuk judul
                $sheet->mergeCells('A1:F1');

                // Format judul
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Border untuk tabel
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
