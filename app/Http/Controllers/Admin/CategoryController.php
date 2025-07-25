<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = 'admin.category.index';
        $title = 'Category List';
        $js = 'category';

        if ($request->ajax()) {
            $data = Category::query()->orderBy('id', 'desc');

            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('name', function ($row) {

                    return '<a href="javascript:void(0);" class="product-img">
                <img src="' . asset($row->category_image) . '" alt="product">
              </a>
              <a href="javascript:void(0);">' . $row->name . '</a>';
                })

                ->addColumn('action', function ($row) {

                    $id = encrypt($row->id);
                    $edit = route('category.edit', $id);
                    $delete = route('category.destroy', $id);
                    return '
                    <a class="me-3" href="#"><img src="' . asset('back/img/icons/eye.svg') . '" alt="img" /></a>
                    <a class="me-3" href="' . $edit . '"><img src="' . asset('back/img/icons/edit.svg') . '" alt="img" /></a>
                    <a class="me-3 confirm-text" data-url="' . $delete . '" href="javascript:void();"><img src="' . asset('back/img/icons/delete.svg') . '" alt="img" /></a>
                ';
                })
                ->rawColumns(['name', 'action'])
                ->make(true);
        }

        return view('admin/layout', compact('page', 'title', 'js'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page = 'admin.category.create';
        $title = 'Create Category';
        $js = 'category';
        return view('admin/layout', compact('page', 'title', 'js'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {

        $category = new Category();
        $category->name = $request->name;
        $slugName = generate_slug($request->name);
        $category->slug = $slugName;


        if ($request->hasFile("category_image")) {
            $image = $request->file("category_image");

            // Store file in 'public/back/img/brand' directory
            $imagePath = $image->store("back/img/category", "public");

            // Save only the relative path to DB
            $category->category_image = 'storage/' . $imagePath;
        }


        $category->save();
        return redirect()->route('category.index')->with('success', 'Category created successfully!');
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
        $brandId = decrypt($id);
        $page = 'admin.category.edit';
        $title = 'Edit Category';
        $js = 'Category';
        $category = Category::findOrFail($brandId);
        $maincatgory = Category::all();
        return view('admin/layout', compact('page', 'title', 'js', 'category', 'maincatgory','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $id = decrypt($id);
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $category = Category::findOrFail($id);
        $category->name = $request->name;



        if ($request->hasFile("category_image")) {
            $filePath  =   public_path($category->category_image);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $image = $request->file("category_image");

            // Store file in 'public/back/img/brand' directory
            $imagePath = $image->store("back/img/category", "public");

            // Save only the relative path to DB
            $category->category_image = 'storage/' . $imagePath;
        }

        $category->save();
        return redirect()->route('category.index')->with('success', 'Cateogory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $id = decrypt($id); // if you are encrypting the ID in route
            $category = Category::findOrFail($id);

            // Optional: Delete the logo file from storage if it exists
            $filePath  =   public_path($category->category_image);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $category->delete(); // Delete the brand record

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkCategoryName(Request $request)
    {
        $name = $request->input('name');
        $id = $request->input('id'); // optional (for update)

        $query = Category::where('name', $name);

        if ($id) {
            $query->where('id', '!=', decrypt($id)); // skip current record if updating
        }

        $exists = $query->exists();

        // CASE 1: If request is from jQuery Validate plugin (remote rule)
        if ($request->ajax() && !$request->has('custom_ajax')) {
            return response()->json(!$exists); // true if name is available
        }

        // CASE 2: Custom Ajax (manual call)
        return response()->json(['exists' => $exists]);
    }
}
