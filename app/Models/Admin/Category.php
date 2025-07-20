<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
       use SoftDeletes;


         public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // 🔁 Parent Category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}


      