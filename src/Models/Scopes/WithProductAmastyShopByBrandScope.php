<?php

namespace Rapidez\AmastyShopByBrand\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Rapidez\Core\RapidezFacade as Rapidez;

class WithProductAmastyShopByBrandScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $attribute = Rapidez::config('amshopby_brand/general/attribute_code', 'manufacturer');

        $builder
            ->selectRaw('MAX(amasty_amshopby_option_setting.image) as amasty_brand_image')
            ->selectRaw("IF(MAX(amasty_amshopby_option_setting.url_alias) !='', CONCAT('/',LOWER(MAX(TRIM(LEADING '/' FROM amasty_amshopby_option_setting.url_alias)))), CONCAT('/', LOWER(REPLACE(REPLACE(".$model->getTable().'.'.$attribute."_value,' ', '_'),'-','_')))) as amasty_brand_url")
            ->leftJoin('amasty_amshopby_option_setting', function ($join) use ($model, $attribute) {
                $join->on($model->getTable().'.'.$attribute, '=', 'amasty_amshopby_option_setting.value')
                     ->where('filter_code', 'attr_'.$attribute);
            });
    }
}
