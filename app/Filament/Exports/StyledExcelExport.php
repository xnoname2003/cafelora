<?php

namespace App\Filament\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class StyledExcelExport extends ExcelExport
{
    public function registerEvents(): array
    {
        // Ambil event yang sudah ada dari parent class (jika ada)
        $parentEvents = parent::registerEvents();

        // Gabungkan event parent dengan event styling kustom kita
        return array_merge($parentEvents, [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 1. Atur style untuk header (baris pertama)
                $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '3B82F6'], // Warna Biru
                    ],
                ]);

                // 2. Tambahkan border ke seluruh tabel
                $range = 'A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow();
                $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // 3. Atur auto-size untuk setiap kolom
                foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            }
        ]);
    }
}