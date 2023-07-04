<?php

namespace Rapidez\AmastyShopByBrand\Http\Controllers;

use Rapidez\AmastyShopByBrand\Resolvers\BrandResolver;

class AmastyShopByBrandController
{
    public function __invoke(BrandResolver $resolver)
    {
        $brand = $resolver->byPath() ?? $resolver->byOptionValue();
        abort_unless($brand, 404);

        return view('amastyshopbybrand::brand-overview', compact('brand'));
    }
}
