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
                <img src="'.asset($row->category_image).'" alt="product">
              </a>
              <a href="javascript:void(0);">'.$row->name.'</a>';
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
        dd($request);

         $category = new Category();
            $category->name = $name;
            $slugName = generate_slug($name);
            $category->slug = $slugName;
        


            if ($request->hasFile("product_image.$index")) {
                $image = $request->file("product_image")[$index];

                // Store file in 'public/back/img/brand' directory
                $imagePath = $image->store("back/img/brand", "public");

                // Save only the relative path to DB
                $brand->logo = 'storage/' . $imagePath;
            }


            $brand->save();
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
}
