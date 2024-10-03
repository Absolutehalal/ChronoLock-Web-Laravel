<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class FacultyAttendanceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithTitle, WithCustomStartCell, WithEvents
{
    protected $collection;
    protected $index = 0;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function collection()
    {
        return $this->collection;
    }

    public function map($attendance): array
    {
        // Format the date
        $formattedDate = \DateTime::createFromFormat('Y-m-d', $attendance->date)->format('F j, Y');

        // Format the time
        if ($attendance->time) {
            $formattedTime = \DateTime::createFromFormat('H:i:s', $attendance->time)->format('g:i A');
        } else {
            $formattedTime = 'No Record';
        }


        // Capitalize the remark
        $remark = strtoupper($attendance->remark);

        return [
            ++$this->index,
            ucwords($attendance->firstName) . ' ' . ucwords($attendance->lastName),
            ucwords($attendance->userID),
            $attendance->courseCode,
            $attendance->courseName,
            $attendance->program . ' - ' . $attendance->year . $attendance->section,
            $formattedDate,
            $formattedTime,
            $remark,
            // Add other fields you need to map here, e.g., $attendance->field_name,
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Name',
            'User ID',
            'Course Code',
            'Course Name',
            'Program & Section',
            'Date',
            'Time',
            'Remark',
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
            AfterSheet::class => function (AfterSheet $event) {
                // Merge cells for the title
                $event->sheet->mergeCells('A1:I1');
                $event->sheet->setCellValue('A1', 'Faculty Attendance Report');

                // Set the row height for the heading
                $event->sheet->getRowDimension(1)->setRowHeight(60);

                // Apply styles to the title
                $event->sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 30,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Apply bold style to headings
                $event->sheet->getStyle('A2:I2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 13,
                    ],
                ]);

                // Apply conditional formatting for the Remark column (I)
                $conditionalStyles = $event->sheet->getStyle('I3:I' . $event->sheet->getHighestRow())->getConditionalStyles();

                $conditionalPresent = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                $conditionalPresent->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT)
                    ->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_CONTAINSTEXT)
                    ->setText('PRESENT')
                    ->getStyle()
                    ->applyFromArray([
                        'font' => [
                            'color' => ['argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK],
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FF00FF00'], // Green background
                        ],
                    ]);

                $conditionalLate = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                $conditionalLate->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT)
                    ->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_CONTAINSTEXT)
                    ->setText('LATE')
                    ->getStyle()
                    ->applyFromArray([
                        'font' => [
                            'color' => ['argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK],
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFFFFF00'], // Yellow background
                        ],
                    ]);

                $conditionalAbsent = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                $conditionalAbsent->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT)
                    ->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_CONTAINSTEXT)
                    ->setText('ABSENT')
                    ->getStyle()
                    ->applyFromArray([
                        'font' => [
                            'color' => ['argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE],
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFFF0000'], // Red background
                        ],
                    ]);

                $conditionalStyles[] = $conditionalPresent;
                $conditionalStyles[] = $conditionalLate;
                $conditionalStyles[] = $conditionalAbsent;

                $event->sheet->getStyle('I3:I' . $event->sheet->getHighestRow())->setConditionalStyles($conditionalStyles);

                // Apply border to the entire table
                $event->sheet->getStyle('A1:I' . $event->sheet->getHighestRow())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // Insert logo on the left side of the heading
                $drawingLeft = new Drawing();
                $drawingLeft->setName('Logo Left');
                $drawingLeft->setDescription('Logo Left');
                $drawingLeft->setPath(public_path('images/CSPC.png')); // Path to your logo image
                $drawingLeft->setHeight(70);
                $drawingLeft->setCoordinates('A1');
                $drawingLeft->setOffsetX(5);
                $drawingLeft->setOffsetY(5);
                $drawingLeft->setWorksheet($event->sheet->getDelegate());

                // Insert logo on the right side of the heading
                $drawingRight = new Drawing();
                $drawingRight->setName('Logo Right');
                $drawingRight->setDescription('Logo Right');
                $drawingRight->setPath(public_path('images/CCS.png')); // Path to your logo image
                $drawingRight->setHeight(70);
                $drawingRight->setCoordinates('I1');
                $drawingRight->setOffsetX(-5);
                $drawingRight->setOffsetY(5);
                $drawingRight->setWorksheet($event->sheet->getDelegate());

                // Fit sheet to one page
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}
