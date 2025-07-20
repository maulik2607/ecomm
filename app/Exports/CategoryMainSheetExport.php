<?php 
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

class CategoryMainSheetExport implements FromArray, WithHeadings, WithTitle, WithEvents
{
    public function title(): string
    {
        return 'CategoryData';
    }

    public function headings(): array
    {
        return ['name', 'parent_name'];
    }

    public function array(): array
    {
        return array_fill(0, 20, ['', '']); // 20 empty rows
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                // Dropdown in column B
                for ($row = 2; $row <= 100; $row++) {
                    $event->sheet->getDelegate()->getCell("B$row")->getDataValidation()
                        ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                        ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP)
                        ->setAllowBlank(true)
                        ->setShowDropDown(true)
                        ->setFormula1('=Dropdowns!$A$2:$A$100');
                }
            },
        ];
    }
}


?>