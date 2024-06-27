<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\Request;

class InstructorAttendanceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithTitle, WithCustomStartCell, WithEvents
{
    protected $index = 0;
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Pass the request data to the getRecord method
        return Attendance::getRecord($this->request);
    }

    public function map($attendance): array
    {
        return [
            ++$this->index,
            $attendance->date,
            $attendance->time,
            $attendance->remark,
            $attendance->firstName . ' ' . $attendance->lastName,
            // Add other fields you need to map here, e.g., $attendance->field_name,
        ];
    }

    public function headings(): array
    {
        return [
            'S. No',
            'Date',
            'Time',
            'Remark',
            'Name',
            // Add other headings you need here, e.g., 'Field Name',
        ];
    }

    public function title(): string
    {
        return 'Instructor Attendance Report';
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Merge cells for the title
                $event->sheet->mergeCells('A1:E1');
                $event->sheet->setCellValue('A1', 'Instructor Attendance Report');
                // Apply styles to the title
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Apply bold style to headings
                $event->sheet->getStyle('A2:E2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 13,
                    ],
                ]);
            },
        ];
    }
}
