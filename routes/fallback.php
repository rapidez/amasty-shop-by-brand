<?php

if ($brand = DB::table('amasty_amshopby_option_setting')
    ->where(function ($query) {
        $query->where('url_alias', '/'.request()->path())
            ->orWhere('url_alias', request()->path());
    })
    ->where('store_id', 0)
    ->first()) {
    $option = DB::table('eav_attribute_option_value')
        ->where('option_id', $brand->value)
        ->first();

    $brand->name = $option->value;

    echo view('amastyshopbybrand::brand-overview', compact('brand'));
<<<<<<< HEAD

=======
>>>>>>> 0aad2c1d6b1a5d0b35d4f603f8c94ece80e402d7
} elseif ($option = DB::table('eav_attribute_option')
    ->join('eav_attribute', 'eav_attribute.attribute_id', '=', 'eav_attribute_option.attribute_id')
    ->where('eav_attribute.attribute_code', Rapidez::config('amshopby_brand/general/attribute_code', 'manufacturer'))
    ->join('eav_attribute_option_value', 'eav_attribute_option_value.option_id', '=', 'eav_attribute_option.option_id')
    ->where('store_id', 0)
    ->where(function ($query) {
        $query->where('value', str_replace('_', ' ', request()->path()))
              ->orWhere('value', str_replace('_', '-', request()->path()));
    })
    ->first()) {
    if ($brand = DB::table('amasty_amshopby_option_setting')
        ->where('value',$option->option_id)
        ->where('store_id', 0)
        ->first()) {
        $brand->name = $option->value;

        echo view('amastyshopbybrand::brand-overview', compact('brand'));
    }
}
