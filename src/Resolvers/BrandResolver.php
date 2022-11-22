<?php

namespace Rapidez\AmastyShopByBrand\Resolvers;

use Illuminate\Support\Facades\DB;
use Rapidez\Core\Facades\Rapidez;

class BrandResolver
{
    public function byPath(string $path = ''): ?object
    {
        $path = $path ?: request()->path();
        $brand = DB::table('amasty_amshopby_option_setting')
            ->select('*')
            ->selectSub(
                DB::table('catalog_product_flat_'.config('rapidez.store'))
                ->selectRaw('COUNT(*)')
                ->whereColumn(Rapidez::config('amshopby_brand/general/attribute_code', 'manufacturer'), 'value'),
                'product_count'
            )
            ->where(function ($query) use ($path) {
                $query->where('url_alias', '/'.$path)
                    ->orWhere('url_alias', $path);
            })
            ->where('store_id', 0)
            ->first();

        if (!$brand) {
            return $brand;
        }

        $option = DB::table('eav_attribute_option_value')
                ->where('option_id', $brand->value)
                ->first();

        $brand->name = $option->value;

        return $brand;
    }

    public function byOptionValue(string $path = ''): ?object
    {
        $path = $path ?: request()->path();
        $option = DB::table('eav_attribute_option')
            ->join('eav_attribute', 'eav_attribute.attribute_id', '=', 'eav_attribute_option.attribute_id')
            ->where('eav_attribute.attribute_code', Rapidez::config('amshopby_brand/general/attribute_code', 'manufacturer'))
            ->join('eav_attribute_option_value', 'eav_attribute_option_value.option_id', '=', 'eav_attribute_option.option_id')
            ->where('store_id', 0)
            ->where(function ($query) use ($path) {
                $query->where('value', str_replace('_', ' ', $path))
                    ->orWhere('value', str_replace('-', ' ', $path))
                    ->orWhere('value', str_replace('_', '-', $path));
            })
            ->first();

        if (
            !$option ||
            !str($option->value)->lower()->is($path) &&
            !str($option->value)->slug()->is(str($path)->lower())
        ) {
            return null;
        }

        $brand = DB::table('amasty_amshopby_option_setting')
            ->select('*')
            ->selectSub(
                DB::table('catalog_product_flat_'.config('rapidez.store'))
                    ->selectRaw('COUNT(*)')
                    ->where($option->attribute_code, $option->option_id),
                'product_count'
            )
            ->where('value', $option->option_id)
            ->where('store_id', 0)
            ->first();

        $brand->name = $option->value;

        return $brand;
    }

    public static function bind(): void
    {
        app()->singleton(BrandResolver::class, static::class);
    }
}
