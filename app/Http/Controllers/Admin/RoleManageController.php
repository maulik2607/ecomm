<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
class RoleManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
    {
        $page = 'admin.role.index';
        $title = 'Role List';
        $js = 'role';

        if ($request->ajax()) {
            $data = Role::query()->orderBy('id', 'desc');

            return Datatables::of($data)
                ->addIndexColumn()

            
                ->addColumn('action', function ($row) {

                    $id = encrypt($row->id);
                    $edit = route('role.edit', $id);
                    $delete = route('role.destroy', $id);
                    return '
                    <a class="me-3" href="#"><img src="' . asset('back/img/icons/eye.svg') . '" alt="img" /></a>
                    <a class="me-3" href="' . $edit . '"><img src="' . asset('back/img/icons/edit.svg') . '" alt="img" /></a>
                    <a class="me-3 confirm-text" data-url="' . $delete . '" href="javascript:void();"><img src="' . asset('back/img/icons/delete.svg') . '" alt="img" /></a>
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
