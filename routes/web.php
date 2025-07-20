<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\PreventBackHistory;
use App\Exports\CategoryTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
Route::get('/', function () {
  return view('welcome');
});


Route::get('/admin', [LoginController::class, 'index'])->name('login');
Route::post('/admin-login', [LoginController::class, 'autheticate'])->name('admin.autheticate');

Route::middleware(['auth', PreventBackHistory::class])->group(function () {
  Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard')->middleware(RoleMiddleware::class . ':admin');

  Route::middleware(RoleMiddleware::class . ':admin')->prefix('admin')->group(function () {
    Route::resource('brand', BrandController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('subcategory', SubcategoryController::class);

Route::get('/export-category-template', [SubcategoryController::class, 'exportCategoryTemplate'])
    ->name('categories.template');

  });
  
  
  Route::post('/admin/brand/export-pdf', [BrandController::class, 'exportPdf'])->name('admin.brand.exportPdf');
  Route::post('/admin/brand/export-excel', [BrandController::class, 'exportExcel'])->name('admin.brand.exportExcel');
  Route::post('/admin/brand/check-brand', [BrandController::class, 'checkBrandName'])->name('brand.checkName');
  Route::post('/admin/category/check-category', [CategoryController::class, 'checkCategoryName'])->name('category.checkName');
  Route::post('/admin/category/check-subcategory', [SubcategoryController::class, 'checkSubcategory']);


  Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
