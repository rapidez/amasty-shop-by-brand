<?php

namespace Rapidez\AmastyShopByBrand\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rapidez\Core\Facades\Rapidez;
use Rapidez\AmastyShopByBrand\Resolvers\BrandResolver;

class AmastyShopByBrandController
{
    public function __invoke(BrandResolver $resolver)
    {
        $brand = $resolver->byPath() ?? $resolver->byOptionValue();

        if ($brand) {
            return view('amastyshopbybrand::brand-overview', compact('brand'));
        }
    }
}
