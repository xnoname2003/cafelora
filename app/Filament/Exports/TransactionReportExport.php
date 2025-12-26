<?php

namespace App\Filament\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TransactionReportExport implements FromView, WithTitle, ShouldAutoSize, WithEvents
{
    public function __construct(
        public Builder $query,
        protected string $format = 'pdf',
        protected ?string $startDate = null,
        protected ?string $endDate = null
    ) {
        //
    }

    public function view(): View
    {
        $rows = $this->query->with(['user', 'payments'])->get();

        $totalSales = $rows->sum('total');

        $startDate = $this->startDate
            ? Carbon::parse($this->startDate)->format('d M Y')
            : ($rows->isNotEmpty() ? $rows->last()->created_at->format('d M Y') : 'N/A');
        $endDate = $this->endDate
            ? Carbon::parse($this->endDate)->format('d M Y')
            : ($rows->isNotEmpty() ? $rows->first()->created_at->format('d M Y') : 'N/A');

        return view('exports.transaction-report', [
            'rows' => $rows,
            'totalSales' => $totalSales,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'isExcel' => $this->format === 'xlsx',
        ]);
    }

    /**
    * @return string
    */
    public function title(): string
    {
        return 'Laporan Penjualan';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                $headerRow = null;
                for ($row = 1; $row <= $highestRow; $row++) {
                    $value = (string) $sheet->getCell("A{$row}")->getValue();
                    if (trim($value) === 'Tanggal') {
                        $headerRow = $row;
                        break;
                    }
                }

                $headerRow ??= 1;
                $tableRange = "A{$headerRow}:{$highestColumn}{$highestRow}";
                $footerRow = $highestRow;
                for ($row = $highestRow; $row >= $headerRow; $row--) {
                    $value = (string) $sheet->getCell("A{$row}")->getValue();
                    if (trim($value) === 'Total Penjualan') {
                        $footerRow = $row;
                        break;
                    }
                }

                $sheet->getStyle("A{$headerRow}:{$highestColumn}{$headerRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F2937'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle("A{$footerRow}:{$highestColumn}{$footerRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F2937'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle($tableRange)->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle($tableRange)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
                $sheet->setAutoFilter($tableRange);
                $sheet->freezePane('A' . ($headerRow + 1));

                $sheet->getColumnDimension('A')->setWidth(20);
                $sheet->getColumnDimension('B')->setWidth(34);
                $sheet->getColumnDimension('C')->setWidth(20);
                $sheet->getColumnDimension('D')->setWidth(12);
                $sheet->getColumnDimension('E')->setWidth(12);
                $sheet->getColumnDimension('F')->setWidth(16);

                if ($this->format === 'xlsx') {
                    $sheet->getStyle("F" . ($headerRow + 1) . ":F{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }
            },
        ];
    }
}