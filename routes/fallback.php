<?php
if ($option = DB::table('eav_attribute_option')
    ->join('eav_attribute_option_value', 'eav_attribute_option_value.option_id', '=', 'eav_attribute_option.option_id')
    ->where('attribute_id', 83)
    ->where('store_id', 0)
    ->where(function ($query) {
        $query->where('value', str_replace('_', ' ', request()->path()))
              ->orWhere('value', str_replace('_', '-', request()->path()));
    })
    ->first()) {
    if ($brand = DB::table('amasty_amshopby_option_setting')
        ->where('value', $option->option_id)
        ->where('store_id', 0)
        ->first()) {
        $brand->name = $option->value;

        echo view('amastyshopbybrand::brand-overview', compact('brand'));
    }
}
