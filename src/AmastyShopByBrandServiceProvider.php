<?php

namespace Rapidez\AmastyShopByBrand;

use Illuminate\Support\ServiceProvider;
use Rapidez\AmastyShopByBrand\Models\Scopes\WithProductAmastyShopByBrandScope;
use TorMorten\Eventy\Facades\Eventy;

class AmastyShopByBrandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'amastyshopbybrand');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/rapidez/amasty-shop-by-brand'),
        ], 'views');

        Eventy::addFilter('routes', fn ($routes) => array_merge($routes ?: [], [__DIR__.'/../routes/fallback.php']));
        Eventy::addFilter('product.scopes', fn ($scopes) => array_merge($scopes ?: [], [WithProductAmastyShopByBrandScope::class]));
    }
}
