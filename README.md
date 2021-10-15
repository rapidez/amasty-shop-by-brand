# Rapidez Amasty Shop By Brand

## Requirements

You need to have the [Amasty Shop By Brand](https://amasty.com/shop-by-brand-for-magento-2.html) module installed and configured within your Magento 2 installation. This module comes with the [Improved Layered Navigation](https://amasty.com/improved-layered-navigation-for-magento-2.html) module.

## Installation

```
composer require rapidez/amasty-shop-by-brand
```

After that brands are available at `/some_brand` where all products from that brand will be displayed with the configured meta data and the description. If you want to display the brand image somewhere you can use `$product->amasty_brand_image` or `item.amasty_brand_image` within Reactive Search. For example on a product page:

```
@if($product->amasty_brand_image)
    <a href="/{{ strtolower(str_replace([' ', '-'], '_', $product->manufacturer)) }}">
        <img src="{{ config('rapidez.media_url').'/amasty/shopby/option_images/'.$product->amasty_brand_image }}" alt="{{ $product->manufacturer }}" class="h-10">
    </a>
@endif
```

### Brand list widget

If you want to use the the `Amasty\ShopbyBrand\Block\Widget\BrandList` widget you can register it in the `config/rapidez.php` file. Useful for a brand cms page with all brands listed.
```php
'widgets' => [
    ...
    'Amasty\ShopbyBrand\Block\Widget\BrandList' => Rapidez\AmastyShopByBrand\Widgets\BrandList::class,
],
```

## Views

If you need to change the views you can publish them with:
```
php artisan vendor:publish --provider="Rapidez\AmastyShopByBrand\AmastyShopByBrandServiceProvider" --tag=views
```

## Note

Currently only brand pages, the brand list widget and images are implemented.

## License

GNU General Public License v3. Please see [License File](LICENSE) for more information.
