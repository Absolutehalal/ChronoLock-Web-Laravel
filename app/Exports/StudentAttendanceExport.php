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
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class StudentAttendanceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithTitle, WithCustomStartCell, WithEvents
{
    protected $collection;
    protected $index = 0;
    protected $hasRecords; // To check if there are records

    public function __construct($collection)
    {
        $this->collection = $collection;
        $this->hasRecords = $collection->isNotEmpty(); // Set flag based on whether records exist
    }

    public function collection()
    {
        return $this->collection;
    }

    public function map($attendance): array
    {
        // Handle "No Record Found" case
        if (!$attendance) {
            return ['No Record Found'];
        }

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
            $attendance->courseName,
            $attendance->program,
            $attendance->year . ' - ' . $attendance->section,
            $formattedDate,
            $formattedTime,
            $remark,
        ];
    }

    public function headings(): array
    {
        return [
            'S. No',
            'Name',
            'User ID',
            'Course Name',
            'Program',
            'Year & Section',
            'Date',
            'Time',
            'Remark',
        ];
    }

    public function title(): string
    {
        return 'Student Attendance Report';
    }
    public function startCell(): string
    {
        return 'A2';
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Check if no records are available
                if (!$this->hasRecords) {
                    // Merge and center "No Record Found"
                    $sheet->mergeCells('A3:I3');
                    $sheet->setCellValue('A3', 'No Record Found');
                    $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(15);


                    // Merge cells for the title
                    $sheet->mergeCells('A1:I1');
                    $sheet->setCellValue('A1', 'Student Attendance Report');

                    // Set row height for the heading
                    $sheet->getRowDimension(1)->setRowHeight(60);

                    // Apply styles to the title
                    $sheet->getStyle('A1:I1')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 30,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                    // Apply bold style to headings
                    $sheet->getStyle('A2:I2')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 13,
                        ],
                    ]);

                    // Conditional formatting logic for the remark column
                    // Define conditional formatting rules for Present, Late, and Absent
                    $conditionalStyles = $sheet->getStyle('I3:I' . $sheet->getHighestRow())->getConditionalStyles();

                    $conditionalPresent = new Conditional();
                    $conditionalPresent->setConditionType(Conditional::CONDITION_CONTAINSTEXT)
                        ->setOperatorType(Conditional::OPERATOR_CONTAINSTEXT)
                        ->setText('PRESENT')
                        ->getStyle()
                        ->applyFromArray([
                            'font' => ['color' => ['argb' => Color::COLOR_BLACK]],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF00FF00']], // Green background
                        ]);

                    $conditionalLate = new Conditional();
                    $conditionalLate->setConditionType(Conditional::CONDITION_CONTAINSTEXT)
                        ->setOperatorType(Conditional::OPERATOR_CONTAINSTEXT)
                        ->setText('LATE')
                        ->getStyle()
                        ->applyFromArray([
                            'font' => ['color' => ['argb' => Color::COLOR_BLACK]],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFFF00']], // Yellow background
                        ]);

                    $conditionalAbsent = new Conditional();
                    $conditionalAbsent->setConditionType(Conditional::CONDITION_CONTAINSTEXT)
                        ->setOperatorType(Conditional::OPERATOR_CONTAINSTEXT)
                        ->setText('ABSENT')
                        ->getStyle()
                        ->applyFromArray([
                            'font' => ['color' => ['argb' => Color::COLOR_WHITE]],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFF0000']], // Red background
                        ]);

                    $conditionalStyles[] = $conditionalPresent;
                    $conditionalStyles[] = $conditionalLate;
                    $conditionalStyles[] = $conditionalAbsent;

                    $sheet->getStyle('I3:I' . $sheet->getHighestRow())->setConditionalStyles($conditionalStyles);

                    // Apply border to the entire table
                    $sheet->getStyle('A1:I' . $sheet->getHighestRow())->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FF000000'],
                            ],
                        ],
                    ]);

                    // Insert left logo
                    $drawingLeft = new Drawing();
                    $drawingLeft->setName('Logo Left');
                    $drawingLeft->setDescription('Logo Left');
                    $drawingLeft->setPath(public_path('images/CSPC.png')); // Path to logo
                    $drawingLeft->setHeight(70);
                    $drawingLeft->setCoordinates('A1');
                    $drawingLeft->setOffsetX(5);
                    $drawingLeft->setOffsetY(5);
                    $drawingLeft->setWorksheet($sheet);

                    // Insert right logo
                    $drawingRight = new Drawing();
                    $drawingRight->setName('Logo Right');
                    $drawingRight->setDescription('Logo Right');
                    $drawingRight->setPath(public_path('images/CCS.png')); // Path to logo
                    $drawingRight->setHeight(70);
                    $drawingRight->setCoordinates('I1');
                    $drawingRight->setOffsetX(-5);
                    $drawingRight->setOffsetY(5);
                    $drawingRight->setWorksheet($sheet);

                    // Fit to one page
                    $sheet->getPageSetup()->setFitToWidth(1);
                    $sheet->getPageSetup()->setFitToHeight(0);
                } else {
                    // If there are records, proceed with normal setup

                    // Merge cells for the title
                    $sheet->mergeCells('A1:I1');
                    $sheet->setCellValue('A1', 'Student Attendance Report');

                    // Set row height for the heading
                    $sheet->getRowDimension(1)->setRowHeight(60);

                    // Apply styles to the title
                    $sheet->getStyle('A1:I1')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 30,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                    // Apply bold style to headings
                    $sheet->getStyle('A2:I2')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 13,
                        ],
                    ]);

                    // Conditional formatting logic for the remark column
                    // Define conditional formatting rules for Present, Late, and Absent
                    $conditionalStyles = $sheet->getStyle('I3:I' . $sheet->getHighestRow())->getConditionalStyles();

                    $conditionalPresent = new Conditional();
                    $conditionalPresent->setConditionType(Conditional::CONDITION_CONTAINSTEXT)
                        ->setOperatorType(Conditional::OPERATOR_CONTAINSTEXT)
                        ->setText('PRESENT')
                        ->getStyle()
                        ->applyFromArray([
                            'font' => ['color' => ['argb' => Color::COLOR_BLACK]],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF00FF00']], // Green background
                        ]);

                    $conditionalLate = new Conditional();
                    $conditionalLate->setConditionType(Conditional::CONDITION_CONTAINSTEXT)
                        ->setOperatorType(Conditional::OPERATOR_CONTAINSTEXT)
                        ->setText('LATE')
                        ->getStyle()
                        ->applyFromArray([
                            'font' => ['color' => ['argb' => Color::COLOR_BLACK]],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFFF00']], // Yellow background
                        ]);

                    $conditionalAbsent = new Conditional();
                    $conditionalAbsent->setConditionType(Conditional::CONDITION_CONTAINSTEXT)
                        ->setOperatorType(Conditional::OPERATOR_CONTAINSTEXT)
                        ->setText('ABSENT')
                        ->getStyle()
                        ->applyFromArray([
                            'font' => ['color' => ['argb' => Color::COLOR_WHITE]],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFF0000']], // Red background
                        ]);

                    $conditionalStyles[] = $conditionalPresent;
                    $conditionalStyles[] = $conditionalLate;
                    $conditionalStyles[] = $conditionalAbsent;

                    $sheet->getStyle('I3:I' . $sheet->getHighestRow())->setConditionalStyles($conditionalStyles);

                    // Apply border to the entire table
                    $sheet->getStyle('A1:I' . $sheet->getHighestRow())->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FF000000'],
                            ],
                        ],
                    ]);

                    // Insert left logo
                    $drawingLeft = new Drawing();
                    $drawingLeft->setName('Logo Left');
                    $drawingLeft->setDescription('Logo Left');
                    $drawingLeft->setPath(public_path('images/CSPC.png')); // Path to logo
                    $drawingLeft->setHeight(70);
                    $drawingLeft->setCoordinates('A1');
                    $drawingLeft->setOffsetX(5);
                    $drawingLeft->setOffsetY(5);
                    $drawingLeft->setWorksheet($sheet);

                    // Insert right logo
                    $drawingRight = new Drawing();
                    $drawingRight->setName('Logo Right');
                    $drawingRight->setDescription('Logo Right');
                    $drawingRight->setPath(public_path('images/CCS.png')); // Path to logo
                    $drawingRight->setHeight(70);
                    $drawingRight->setCoordinates('I1');
                    $drawingRight->setOffsetX(-5);
                    $drawingRight->setOffsetY(5);
                    $drawingRight->setWorksheet($sheet);

                    // Fit to one page
                    $sheet->getPageSetup()->setFitToWidth(1);
                    $sheet->getPageSetup()->setFitToHeight(0);
                }
            },
        ];
    }
}