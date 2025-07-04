<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreBrandRequest;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = 'admin.brand.index';
        $title = 'Brand List';
        $js = 'brand';

        if ($request->ajax()) {
            $data = Brand::query()->orderBy('id', 'desc');

            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('logo', function ($row) {
                    if ($row->logo && file_exists(public_path($row->logo))) {
                        return asset($row->logo);
                    }
                    return '';
                })

                ->addColumn('action', function ($row) {

                    $id = encrypt($row->id);
                    $edit = route('brand.edit', $id);
                    $delete = route('brand.destroy', $id);
                    return '
                    <a class="me-3" href="#"><img src="' . asset('back/img/icons/eye.svg') . '" alt="img" /></a>
                    <a class="me-3" href="' . $edit . '"><img src="' . asset('back/img/icons/edit.svg') . '" alt="img" /></a>
                    <a class="me-3 confirm-text" data-url="'.$delete.'" href="javascript:void();"><img src="' . asset('back/img/icons/delete.svg') . '" alt="img" /></a>
                ';
                })
                ->rawColumns(['action', 'logo'])
                ->make(true);
        }

        return view('admin/layout', compact('page', 'title', 'js'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page = 'admin.brand.create';
        $title = 'Create Brand';
        $js = 'brand';
        return view('admin/layout', compact('page', 'title', 'js'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $data = $request->validated();

        foreach ($data['name'] as $index => $name) {
            $brand = new Brand();
            $brand->name = $name;
            $slugName = generate_slug($name);
            $brand->slug = $slugName;
            $brand->description = $data['description'][$index] ?? null;


            if ($request->hasFile("product_image.$index")) {
                $image = $request->file("product_image")[$index];

                // Store file in 'public/back/img/brand' directory
                $imagePath = $image->store("back/img/brand", "public");

                // Save only the relative path to DB
                $brand->logo = 'storage/' . $imagePath;
            }


            $brand->save();
        }
        return redirect()->route('brand.index')->with('success', 'Brands created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brandId = decrypt($id);
        $page = 'admin.brand.edit';
        $title = 'Edit Brand';
        $js = 'brand';
        $brand = Brand::findOrFail($brandId);
        return view('admin/layout', compact('page', 'title', 'js', 'brand', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = decrypt($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $brand = Brand::findOrFail($id);
        $brand->name = $request->name;
        $brand->description = $request->description;


        if ($request->hasFile("product_image")) {
            $filePath  =   public_path($brand->logo);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $image = $request->file("product_image");

            // Store file in 'public/back/img/brand' directory
            $imagePath = $image->store("back/img/brand", "public");

            // Save only the relative path to DB
            $brand->logo = 'storage/' . $imagePath;
        }

        $brand->save();
        return redirect()->route('brand.index')->with('success', 'Brand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */

public function destroy($id)
{
    try {
        $id = decrypt($id); // if you are encrypting the ID in route
        $brand = Brand::findOrFail($id);

        // Optional: Delete the logo file from storage if it exists
         $filePath  =   public_path($brand->logo);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

        $brand->delete(); // Delete the brand record

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}



    public function checkBrandName(Request $request)
{
    $name = $request->input('name');

    // If ID is provided, we are updating a brand
    if ($request->has('id')) {
        $id = decrypt($request->id);

        // Check if the name exists with a different ID
        $exists = Brand::where('name', $name)
            ->where('id', '!=', $id)
            ->exists();
    } else {
        // New brand - just check if name exists
        $exists = Brand::where('name', $name)->exists();
    }

    // If $exists is true (duplicate found), return false (invalid)
    // If $exists is false (name is unique or belongs to same ID), return true (valid)
    return response()->json(!$exists);
}

}
