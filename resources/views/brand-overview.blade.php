@extends('rapidez::layouts.app')

@section('title', $brand->meta_title ?: $brand->title ?: $brand->name)
@section('description', $brand->meta_description)

@section('content')
    <h1 class="font-bold text-3xl mb-5">{{ $brand->title ?: $brand->name }}</h1>

    @if($brand->product_count)
        <x-rapidez::listing query="{ terms: { '{{ Rapidez::config('amshopby_brand/general/attribute_code', 'manufacturer') }}.keyword': ['{{ $brand->name }}'] } }"/>
    @else
        @lang('Currently we do not have any products from this brand.')
    @endif

    <div class="prose prose-green max-w-none">
        @content($brand->description)
    </div>
@endsection
