<?php

namespace Rapidez\AmastyShopByBrand\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rapidez\Core\Facades\Rapidez;
use Rapidez\AmastyShopByBrand\Resolvers\BrandResolver;

class AmastyShopByBrandController
{
    public function __invoke(Request $request)
    {
        $resolver = app(BrandResolver::class);

        $brand = $resolver->byPath();

        if (!$brand) {
            $brand = $resolver->byOptionValue();
        }

        if($brand) {
            return view('amastyshopbybrand::brand-overview', compact('brand'));
        }
    }
}
