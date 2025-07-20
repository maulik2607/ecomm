<?php 
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CategoryTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new \App\Exports\CategoryMainSheetExport(),
            new \App\Exports\CategoryDropdownSheetExport(),
        ];
    }
}


?>