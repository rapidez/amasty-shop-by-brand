<?php

namespace Rapidez\AmastyShopByBrand;

use Illuminate\Support\ServiceProvider;
use Rapidez\AmastyShopByBrand\Http\Controllers\AmastyShopByBrandController;
use Rapidez\AmastyShopByBrand\Models\Scopes\WithProductAmastyShopByBrandScope;
use Rapidez\AmastyShopByBrand\Resolvers\BrandResolver;
use Rapidez\Core\Facades\Rapidez;
use TorMorten\Eventy\Facades\Eventy;

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

    public function register(): void
    {
        $this->registerBindings();
    }

    protected function registerBindings(): static
    {
        BrandResolver::bind();

        return $this;
    }
}
