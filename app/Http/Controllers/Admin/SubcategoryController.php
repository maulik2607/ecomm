<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Category;
use App\Http\Requests\StoreSubcategoryRequest;
use DataTables;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
          $page = 'admin.subcategory.index';
        $title = 'Subcategory List';
        $js = 'subcategory';

        if ($request->ajax()) {
        $data = Category::with('parent')
            ->whereNotNull('parent_id')
            ->orderBy('id', 'desc');

        return DataTables::of($data)
            
            ->addColumn('parent_name', function ($row) {
                return $row->parent ? $row->parent->name : '-';
            })

            ->addColumn('action', function ($row) {
                $id = encrypt($row->id);
                $edit = route('subcategory.edit', $id);
                $delete = route('subcategory.destroy', $id);

                return '
                    <a class="me-3" href="#"><img src="' . asset('back/img/icons/eye.svg') . '" alt="view" /></a>
                    <a class="me-3" href="' . $edit . '"><img src="' . asset('back/img/icons/edit.svg') . '" alt="edit" /></a>
                    <a class="me-3 confirm-text" data-url="' . $delete . '" href="javascript:void(0);">
                        <img src="' . asset('back/img/icons/delete.svg') . '" alt="delete" />
                    </a>';
            })

            ->rawColumns(['parent_name', 'action'])
            ->make(true);
    }

        return view('admin/layout', compact('page', 'title', 'js'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
          $page = 'admin.subcategory.create';
        $title = 'Create subCategory';
        $js = 'subcategory';
        $allCategories = Category::all();
        return view('admin/layout', compact('page', 'title', 'js','allCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubcategoryRequest $request)
    {
       // dd($request);

        $storeSubcat = new Category();
        $storeSubcat->name = $request->name;
        $slugName = generate_slug($request->name);
        $storeSubcat->slug = $slugName;
        $storeSubcat->parent_id = $request->parent_id;
        $storeSubcat->save();

   return redirect()->route('subcategory.index')->with('success', 'SubCategory created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function checkSubcategory(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'parent_id' => 'required|integer|exists:categories,id',
    ]);

    $exists = Category::where('name', $validated['name'])
                ->where('parent_id', $validated['parent_id'])
                ->exists();

    if ($exists) {
        return response()->json([
            'exists' => true,
            'message' => 'Subcategory already exists under this category.'
        ]);
    } else {
        return response()->json([
            'exists' => false,
            'message' => 'Subcategory is available.'
        ]);
    }
}

 public function exportCategoryTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('CategoryData');

        // Header Row
        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Main Category');
      

        // Fetch main categories from DB
        $mainCategories = Category::pluck('name')->toArray();

        // Apply dropdown to column B
        for ($i = 2; $i <= 50; $i++) {
            $validation = $sheet->getCell("B$i")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('\'Dropdowns\'!$A$2:$A$' . (count($mainCategories) + 1));
        }

        // Dropdown values on second sheet
        $dropdownSheet = $spreadsheet->createSheet();
        $dropdownSheet->setTitle('Dropdowns');
        $dropdownSheet->setCellValue('A1', 'Parent Category');

        $row = 2;
        foreach ($mainCategories as $name) {
            $dropdownSheet->setCellValue("A$row", $name);
            $row++;
        }

        // Write to browser directly
        $writer = new Xlsx($spreadsheet);

        // Clear output buffer (very important!)
        ob_end_clean();

        // Send output directly
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="category-template.xlsx"');
        $writer->save('php://output');
        exit;
    }
}
