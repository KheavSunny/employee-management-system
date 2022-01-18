<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements
    ShouldAutoSize,
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    WithColumnWidths
{
    use Exportable;

    protected $fileName;

    // /**
    // * @return \Illuminate\Support\Collection
    // */

    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    public function headings(): array
    {
        return [
            'First Name',
            'BirthDay',
            'Address',
            'Hired Day',
        ];
    }

    // public function view(): View
    // {
    //     return view('system-mgmt.report.employeesExport',[
    //         'employees' => $this->employees
    //     ]);

    // }

    // public function array(): array
    // {
    //     return $this->employees;
    // }

    public function collection()
    {
        return new Collection($this->employees);
    }
    
    public function map($row): array
    {
        return [
            $row['firstname'],
            $row['birthdate'],
            $row['address'],
            $row['date_hired'],
        ];
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function(AfterSheet $event) {
    //             $header = 'A1:D1' ;
    //             $event->sheet
    //             ->getDelegate()
    //             ->getStyle($header)->applyFromArray([
    //                 'font' => [
    //                     'bold' => true,
    //                 ],
    //             ])
    //             ->getFont()->setSize(20);
    //         },
    //     ];
    // }
    
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(20);
    }

    public function columnWidths(): array
    {
        return [
            'C' => 50
        ];
    }

    public function title(): string
    {
        return 'Employee Lists';
    }
}
