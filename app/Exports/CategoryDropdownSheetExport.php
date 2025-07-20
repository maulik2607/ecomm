<?php 

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\Admin\Category;
class CategoryDropdownSheetExport implements FromArray, WithTitle
{
    public function title(): string
    {
        return 'Dropdowns';
    }

  public function array(): array
    {
        // First row is header
        $rows[] = ['Parent Category'];

        // Get main categories from database
        $categories = Category::pluck('name')->toArray();

        // Push each name as a separate row
        foreach ($categories as $name) {
            $rows[] = [$name];
        }

        return $rows;
    }
}


?>