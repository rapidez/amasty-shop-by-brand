</div>

@foreach(range('A','Z') as $letter)
    <a href="#{{ $letter }}" data-turbolinks="false">{{ $letter }}</a>
@endforeach

<hr class="my-5">

@foreach(range('A','Z') as $letter)
    @foreach($brands->filter(fn($brand) => strtoupper($brand->label[0]) == $letter) as $brand)
        @if($loop->first)
            <strong id="{{ $letter }}" class="block text-lg">{{ $letter }}</strong>
            <ul class="flex flex-wrap -mx-2 my-5">
        @endif

        <li class="flex w-1/2 sm:w-1/6 px-2 mb-2">
            <a href="/{{ !empty($brand->url_alias) ? ltrim($brand->url_alias,'/') : strtolower(str_replace(' ', '_', $brand->label)) }}" class="flex flex-1 w-full items-center justify-center border rounded p-3 hover:border-primary transition duration-150 ease-in-out overflow-hidden h-[130px] md:h-[170px]">
                @if($brand->image)
                    <img src="{{ config('rapidez.media_url').'/amasty/shopby/option_images/'.$brand->image }}" class="max-h-[100px] md:max-h-[130px] lg:max-h-[170px] w-auto self-center" alt="{{ $brand->label }}"/>
                @else
                    {{ $brand->label }}
                @endif
            </a>
        </li>

        @if($loop->last)
            </ul>
        @endif
    @endforeach
@endforeach

{{-- See page/overview.blade.php, we're breaking out of the prose --}}
<div class="mb-5 prose prose-green max-w-none">
