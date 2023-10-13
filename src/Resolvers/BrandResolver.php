<?php

namespace Rapidez\AmastyShopByBrand\Resolvers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Rapidez\AmastyShopByBrand\Models\AmastyAmshopbyOptionSetting;
use Rapidez\Core\Facades\Rapidez;

class BrandResolver
{
    public function byPath(string $path = ''): ?object
    {
        $path = $path ?: request()->path();

        $brand = AmastyAmshopbyOptionSetting::with(['optionValue', 'defaultAmshopbyOptionSetting'])
            ->withCount('products')
            ->where(fn ($query) => $query
                ->where('store_id', config('rapidez.store'))
                ->where(fn ($query) => $query
                    ->where('url_alias', '/' . $path)
                    ->orWhere('url_alias', $path)
                    ->orWhereHas('defaultAmshopbyOptionSetting', fn ($query) => $query
                        ->where('url_alias', '/' . $path)
                        ->orWhere('url_alias', $path)
                    )
                )
            )
            ->orWhere(fn ($query) => $query
                ->where('store_id', 0)
                ->where(fn ($query) => $query
                    ->where('url_alias', '/' . $path)
                    ->orWhere('url_alias', $path)
                )
            )
            ->orderByDesc('store_id')
            ->first()
            ?->fillMissing();

        return $brand;
    }

    public function byOptionValue(string $path = ''): ?object
    {
        $path = $path ?: request()->path();

        $attributes = config('rapidez.models.attribute')::getCachedWhere(fn($attribute) => $attribute['code'] === Rapidez::config('amshopby_brand/general/attribute_code', 'manufacturer'));
        if (!count($attributes)) {
            return null;
        }
        $attributeId = reset($attributes)['id'];

        return AmastyAmshopbyOptionSetting::with(['optionValue', 'defaultAmshopbyOptionSetting'])
        ->withCount('products')
        ->where(fn ($query) => $query
            ->whereIn('store_id', [0, config('rapidez.store')])
            ->whereHas('optionValue', fn($query) => $query
                ->where(fn ($query) => $query
                    ->where('value', str_replace('_', ' ', $path))
                    ->orWhere('value', str_replace('-', ' ', $path))
                    ->orWhere('value', str_replace('_', '-', $path))
                )
                ->whereIn('option_id', DB::table('eav_attribute_option')->select('option_id')->where('attribute_id', $attributeId))
            )
        )
        ->orderByDesc('store_id')
        ->first()
        ?->fillMissing();
    }

    public static function bind(): void
    {
        app()->singleton(BrandResolver::class, static::class);
    }

    public function getRawSelect(): string
    {
        $rawSelect = '';
        foreach (Schema::getColumnListing('amasty_amshopby_option_setting') ?? [] as $column) {
            $rawSelect .= "COALESCE(NULLIF(store_option.$column, ''), main_option.$column) as $column,";
        }

        return rtrim($rawSelect, ',');
    }
}
