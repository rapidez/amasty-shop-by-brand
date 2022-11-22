<?php

namespace Rapidez\AmastyShopByBrand\Http\Controllers;

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
