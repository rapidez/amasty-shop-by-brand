<?php

namespace Rapidez\AmastyShopByBrand\Models;

use Illuminate\Database\Eloquent\Model;
use Rapidez\Core\Facades\Rapidez;

class AmastyAmshopbyOptionSetting extends Model
{
    protected $table = 'amasty_amshopby_option_setting';

    protected $primaryKey = 'option_setting_id';

    public function getNameAttribute()
    {
        $this->loadMissing(['optionValue']);

        return $this->optionValue->value;
    }

    public function fillMissing()
    {
        $this->loadMissing(['defaultAmshopbyOptionSetting']);

        foreach (array_filter($this->getAttributes(), fn ($val) => empty($val) && $val !== 0) as $attribute => $value) {
            $this->$attribute = $this->defaultAmshopbyOptionSetting?->$attribute;
        }

        return $this;
    }

    public function defaultAmshopbyOptionSetting()
    {
        return $this->hasOne(
            AmastyAmshopbyOptionSetting::class,
            'value',
            'value'
        )
        ->where('store_id', 0);
    }

    public function products() 
    {
        return $this->hasMany(
            config('rapidez.models.product'),
            Rapidez::config('amshopby_brand/general/attribute_code', 'manufacturer'),
            'value',
        )->withoutGlobalScopes();
    }

    public function optionValue() 
    {
        return $this->hasOne(
            config('rapidez.models.optionvalue'),
            'option_id',
            'value',
        )
        ->withoutGlobalScopes();
    }
}
