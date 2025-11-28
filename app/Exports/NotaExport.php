<?php

namespace App\Exports;

use App\Models\Nota;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NotaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected ?string $startDate;
    protected ?string $endDate;

    protected float $totalKeseluruhan = 0;
    protected float $totalSisa = 0;
    protected int $jumlahNota = 0;
    protected float $totalTerbayar = 0;
    protected float $totalDiskon = 0;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Nota::with(['user', 'kasir', 'payments'])
            ->orderBy('tgl_masuk', 'desc');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_masuk', [$this->startDate, $this->endDate]);
        }

        $data = $query->get();

        $this->jumlahNota = $data->count();
        $this->totalKeseluruhan = (float) $data->sum('total');
        $this->totalSisa = (float) $data->sum('sisa');
        $this->totalTerbayar = (float) $data->sum(function ($nota) {
            return $nota->payments->sum('amount');
        });
        $this->totalDiskon = (float) $data->sum(function ($nota) {
            return $nota->payments->sum('discount_amount');
        });

        return $data;
    }

    public function map($nota): array
    {
        $total = (float) ($nota->total ?? 0);
        $sisa = (float) ($nota->sisa ?? 0);

        $payments = $nota->payments ?? collect();
        $totalTerbayar = (float) $payments->sum('amount');
        $totalDiskon = (float) $payments->sum('discount_amount');

        $cash = (float) $payments->where('type', 'cash')->sum('amount');
        $transfer = (float) $payments->where('type', 'transfer')->sum('amount');

        if ($cash > 0 && $transfer > 0) {
            $metode = 'Cash & Transfer';
        } elseif ($cash > 0) {
            $metode = 'Cash';
        } elseif ($transfer > 0) {
            $metode = 'Transfer';
        } else {
            $metode = '-';
        }

        $status = $sisa <= 0 ? 'Lunas' : 'Belum Lunas';

        $kasir = $nota->kasir_name
            ?? ($nota->kasir->name ?? ($nota->user->name ?? 'Tidak Diketahui'));

        $num = fn ($v) => $v;

        return [
            $nota->id,
            $nota->customer_name ?? '-',
            $nota->tgl_keluar ? Carbon::parse($nota->tgl_keluar)->format('d/m/Y') : '-',
            $num($total),
            $num($totalTerbayar),
            $num($totalDiskon),
            $num($sisa),
            $metode,
            $status,
            $kasir,
        ];
    }

    public function headings(): array
    {
        return [
            'ID Nota',
            'Nama Pelanggan',
            'Tanggal Keluar',
            'Total Awal (Rp)',
            'Terbayar (Rp)',
            'Diskon (Rp)',
            'Sisa (Rp)',
            'Metode Pembayaran',
            'Status',
            'Kasir',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFDDEBF7'],
            ],
        ]);

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 3);
                $sheet->mergeCells('A1:K1');
                $sheet->mergeCells('A2:K2');

                $sheet->setCellValue('A1', 'LAPORAN DATA NOTA DEVA LAUNDRY');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 15,
                        'color' => ['argb' => 'FF1F4E78'],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $tanggalLaporan = Carbon::now()->translatedFormat('d F Y');
                $sheet->setCellValue('A2', 'Tanggal Laporan: ' . $tanggalLaporan);
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 11,
                        'color' => ['argb' => 'FF555555'],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $lastRow = $sheet->getHighestRow();
                $footerRow = $lastRow + 2;

                $sheet->setCellValue("B{$footerRow}", 'TOTAL KESELURUHAN');
                $sheet->setCellValue("C{$footerRow}", 'Jumlah Nota: ' . $this->jumlahNota);
                $sheet->setCellValue("D{$footerRow}", $this->totalKeseluruhan);
                $sheet->setCellValue("E{$footerRow}", $this->totalTerbayar);
                $sheet->setCellValue("F{$footerRow}", $this->totalDiskon);
                $sheet->setCellValue("G{$footerRow}", $this->totalSisa);

                $sheet->getStyle("D4:G{$footerRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0;[Red]"Rp" -#,##0');

                $sheet->getStyle("A4:K{$footerRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFCCCCCC'],
                        ],
                    ],
                ]);

                $sheet->getStyle('A4:K4')->applyFromArray([
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

                $sheet->getStyle("B{$footerRow}:K{$footerRow}")->applyFromArray([
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

                $sheet->getStyle("D5:H{$footerRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $sheet->getStyle("A5:A{$footerRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C5:C{$footerRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("I5:K{$footerRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
