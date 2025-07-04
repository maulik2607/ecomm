<?php

use Illuminate\Support\Str;


    function generate_slug($brandName) {
        $slug = strtolower(trim($brandName));
        $slug = preg_replace('/\s+/', '_', $slug); // Replace spaces with underscores
        return $slug;
    }

    
