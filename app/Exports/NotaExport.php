<?php

namespace App\Exports;

use App\Models\Nota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

// ✅ Tambahan untuk fitur format dan styling Excel
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class NotaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $startDate;
    protected $endDate;

    // ✅ Variabel tambahan untuk hitung total
    protected $totalKeseluruhan = 0;
    protected $totalUangMuka = 0;
    protected $totalSisa = 0;
    protected $jumlahNota = 0;

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

        $data = $query->get();

        // ✅ Tambahan: hitung total keseluruhan
        $this->jumlahNota = $data->count();
        $this->totalKeseluruhan = $data->sum('total');
        $this->totalUangMuka = $data->sum('uang_muka');
        $this->totalSisa = $data->sum('sisa');

        return $data;
    }

    public function map($nota): array
    {
        $total = $nota->total ?? 0;
        $uangMuka = $nota->uang_muka ?? 0;
        $sisa = $nota->sisa ?? 0;

        // ✅ Ambil nama kasir dari kolom atau relasi
        $kasir = $nota->kasir_name 
            ?? ($nota->kasir->name ?? ($nota->user->name ?? 'Tidak Diketahui'));

        // ✅ Biarkan Excel yang memformat angka dengan format akuntansi Rp
        $formatNumber = function ($number) {
            return $number;
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
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFDDEBF7'],
            ],
        ]);

        // ✅ Auto-size setiap kolom
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    // ✅ Tambahan: event styling lengkap, format Rp, total, dan tanggal laporan
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ✅ Sisipkan 3 baris di atas untuk judul dan tanggal
                $sheet->insertNewRowBefore(1, 3);
                $sheet->mergeCells('A1:G1');
                $sheet->mergeCells('A2:G2');

                // ✅ Tambahkan judul laporan
                $sheet->setCellValue('A1', 'LAPORAN DATA NOTA DEVA LAUNDRY');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 15,
                        'color' => ['argb' => 'FF1F4E78']
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ✅ Tambahkan tanggal laporan otomatis
                $tanggalLaporan = Carbon::now()->translatedFormat('d F Y');
                $sheet->setCellValue('A2', 'Tanggal Laporan: ' . $tanggalLaporan);
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['italic' => true, 'size' => 11, 'color' => ['argb' => 'FF555555']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ✅ Baris terakhir data dan baris total
                $lastRow = $sheet->getHighestRow();
                $footerRow = $lastRow + 2;

                // ✅ Tambahkan baris total keseluruhan
                $sheet->setCellValue("B{$footerRow}", 'TOTAL KESELURUHAN');
                $sheet->setCellValue("C{$footerRow}", 'Jumlah Nota: ' . $this->jumlahNota);
                $sheet->setCellValue("D{$footerRow}", $this->totalKeseluruhan);
                $sheet->setCellValue("E{$footerRow}", $this->totalUangMuka);
                $sheet->setCellValue("F{$footerRow}", $this->totalSisa);

                // ✅ Format angka ke bentuk akuntansi Rp
                $sheet->getStyle("D4:F{$footerRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0;[Red]"Rp" -#,##0');

                // ✅ Border seluruh tabel
                $sheet->getStyle("A4:G{$footerRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFCCCCCC'],
                        ],
                    ],
                ]);

                // ✅ Header baris dengan warna lembut
                $sheet->getStyle('A4:G4')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFE2EFDA'],
                    ],
                    'borders' => [
                        'bottom' => ['borderStyle' => Border::BORDER_MEDIUM],
                    ],
                ]);

                // ✅ Styling total keseluruhan (baris bawah)
                $sheet->getStyle("B{$footerRow}:G{$footerRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFFCE4D6'],
                    ],
                    'borders' => [
                        'top' => ['borderStyle' => Border::BORDER_MEDIUM],
                        'bottom' => ['borderStyle' => Border::BORDER_MEDIUM],
                    ],
                ]);

                // ✅ Alignment kolom angka ke kanan
                $sheet->getStyle("D5:F{$footerRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // ✅ Kolom ID, tanggal, kasir diratakan tengah
                $sheet->getStyle("A5:A{$footerRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C5:C{$footerRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("G5:G{$footerRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
