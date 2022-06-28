<?php

namespace Rapidez\AmastyShopByBrand\Widgets;

use Illuminate\Support\Facades\DB;
use Rapidez\Core\Facades\Rapidez;

class BrandList
{
    public function render()
    {
        $attribute = Rapidez::config('amshopby_brand/general/attribute_code', 'manufacturer');

        return view('amastyshopbybrand::widget.brandlist', [
            'brands' => DB::table('amasty_amshopby_option_setting')
                ->selectRaw('
                    ANY_VALUE(eav_attribute_option_value.value) as label,
                    ANY_VALUE(amasty_amshopby_option_setting.url_alias) as url_alias,
                    ANY_VALUE(amasty_amshopby_option_setting.image) as image,
                    COUNT(catalog_product_flat_'.config('rapidez.store').'.'.$attribute.') as count
                ')
                ->join('eav_attribute_option_value', 'amasty_amshopby_option_setting.value', '=', 'eav_attribute_option_value.option_id')
                ->join('catalog_product_flat_'.config('rapidez.store'), 'amasty_amshopby_option_setting.value', '=', 'catalog_product_flat_'.config('rapidez.store').'.'.$attribute)
                ->where('eav_attribute_option_value.store_id', 0)
                ->groupBy('amasty_amshopby_option_setting.value')
                ->get(),
        ])->render();
    }
}
