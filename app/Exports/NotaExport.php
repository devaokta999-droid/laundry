<?php

namespace App\Exports;

use App\Models\Nota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class NotaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Nota::with(['user', 'kasir']) // ✅ tambah relasi kasir jika ada
            ->orderBy('tgl_masuk', 'desc');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_masuk', [$this->startDate, $this->endDate]);
        }

        return $query->get();
    }

    public function map($nota): array
    {
        $total = $nota->total ?? 0;
        $uangMuka = $nota->uang_muka ?? 0;
        $sisa = $nota->sisa ?? 0;

        // ✅ Ambil nama kasir dari kolom atau relasi
        $kasir = $nota->kasir_name 
            ?? ($nota->kasir->name ?? ($nota->user->name ?? 'Tidak Diketahui'));

        // Format angka agar tampil rapi (contoh: 23.500 bukan 23,5)
        $formatNumber = function ($number) {
            return number_format($number, 0, ',', '.');
        };

        return [
            $nota->id,
            $nota->customer_name ?? '-',
            $nota->tgl_keluar ? Carbon::parse($nota->tgl_keluar)->format('d/m/Y') : '-',
            $formatNumber($total),
            $formatNumber($uangMuka),
            $formatNumber($sisa),
            $kasir, // ✅ Tambahan kolom kasir
        ];
    }

    public function headings(): array
    {
        return [
            'ID Nota',
            'Nama Pelanggan',
            'Tanggal Keluar',
            'Total (Rp)',
            'Uang Muka (Rp)',
            'Sisa (Rp)',
            'Kasir', // ✅ Tambahan header kasir
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // ✅ Tambahkan styling agar header tebal dan rapi
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => 'center'],
        ]);

        // ✅ Auto-size setiap kolom
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
