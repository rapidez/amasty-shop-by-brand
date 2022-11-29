<?php

$rawSelect = '';
foreach (Schema::getColumnListing('amasty_amshopby_option_setting') ?? [] as $column) {
    $rawSelect .= "COALESCE(NULLIF(store_option.$column, ''), main_option.$column) as $column,";
}
$rawSelect = rtrim($rawSelect, ',');

if ($rawSelect && $brand = DB::table('amasty_amshopby_option_setting AS main_option')
    ->selectRaw($rawSelect)
    ->selectSub(
        DB::table('catalog_product_flat_'.config('rapidez.store'))
            ->selectRaw('COUNT(*)')
            ->whereColumn(Rapidez::config('amshopby_brand/general/attribute_code', 'manufacturer'), 'main_option.value'),
        'product_count'
    )
    ->leftJoin('amasty_amshopby_option_setting AS store_option', function ($join) {
        $join->on('store_option.value', 'main_option.value')
            ->where('store_option.store_id', config('rapidez.store'));
    })
    ->where(function ($query) {
        $query->where('main_option.url_alias', '/'.request()->path())
            ->orWhere('main_option.url_alias', request()->path());
    })
    ->where('main_option.store_id', 0)
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
    ->where('eav_attribute_option_value.store_id', config('rapidez.store'))
    ->where(function ($query) {
        $query->where('value', str_replace('_', ' ', request()->path()))
              ->orWhere('value', str_replace('-', ' ', request()->path()))
              ->orWhere('value', str_replace('_', '-', request()->path()));
    })
    ->first()) {
    if ($rawSelect && $brand = DB::table('amasty_amshopby_option_setting AS main_option')
        ->selectRaw($rawSelect)
        ->selectSub(
            DB::table('catalog_product_flat_'.config('rapidez.store'))
                ->selectRaw('COUNT(*)')
                ->where($option->attribute_code, $option->option_id),
            'product_count'
        )
        ->leftJoin('amasty_amshopby_option_setting AS store_option', function ($join) {
            $join->on('store_option.value', 'main_option.value')
            ->where('store_option.store_id', config('rapidez.store'));
        })
        ->where('main_option.value', $option->option_id)
        ->where('main_option.store_id', 0)
        ->first()) {
        $brand->name = $option->value;

        echo view('amastyshopbybrand::brand-overview', compact('brand'));
    }
}
