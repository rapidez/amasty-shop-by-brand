<?php

if ($brand = DB::table('amasty_amshopby_option_setting')
    ->select('*')
    ->selectSub(
        DB::table('catalog_product_flat_'.config('rapidez.store'))
            ->selectRaw('COUNT(*)')
            ->whereColumn(Rapidez::config('amshopby_brand/general/attribute_code', 'manufacturer'), 'value'),
        'product_count'
    )
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
} elseif ($option = DB::table('eav_attribute_option')
    ->join('eav_attribute', 'eav_attribute.attribute_id', '=', 'eav_attribute_option.attribute_id')
    ->where('eav_attribute.attribute_code', Rapidez::config('amshopby_brand/general/attribute_code', 'manufacturer'))
    ->join('eav_attribute_option_value', 'eav_attribute_option_value.option_id', '=', 'eav_attribute_option.option_id')
    ->where('store_id', 0)
    ->where(function ($query) {
        $query->where('value', str_replace('_', ' ', request()->path()))
              ->orWhere('value', str_replace('-', ' ', request()->path()))
              ->orWhere('value', str_replace('_', '-', request()->path()));
    })
    ->first()) {
    if ($brand = DB::table('amasty_amshopby_option_setting')
        ->select('*')
        ->selectSub(
            DB::table('catalog_product_flat_'.config('rapidez.store'))
                ->selectRaw('COUNT(*)')
                ->where($option->attribute_code, $option->option_id),
            'product_count'
        )
        ->where('value', $option->option_id)
        ->where('store_id', 0)
        ->first()) {
        $brand->name = $option->value;

        echo view('amastyshopbybrand::brand-overview', compact('brand'));
    }
}
