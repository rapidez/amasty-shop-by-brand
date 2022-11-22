<?php

namespace Rapidez\AmastyShopByBrand;

use Illuminate\Support\ServiceProvider;
use Rapidez\AmastyShopByBrand\Models\Scopes\WithProductAmastyShopByBrandScope;
use TorMorten\Eventy\Facades\Eventy;
use Rapidez\AmastyShopByBrand\Http\Controllers\AmastyShopByBrandController;
use Rapidez\Core\Facades\Rapidez;
use Rapidez\AmastyShopByBrand\Resolvers\BrandResolver;

class AmastyShopByBrandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'amastyshopbybrand');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/amastyshopbybrand'),
        ], 'views');

        Rapidez::addFallbackRoute(AmastyShopByBrandController::class, 10);
        Eventy::addFilter('product.scopes', fn ($scopes) => array_merge($scopes ?: [], [WithProductAmastyShopByBrandScope::class]));
    }

    public function register() : void
    {
        $this->registerBindings();
    }

    protected function registerBindings() : static
    {
        BrandResolver::bind();

        return $this;
    }
}
