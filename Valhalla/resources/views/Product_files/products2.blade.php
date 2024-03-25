<!-- @KraeBM (Bilal Mohamed) worked on the blade templating of this website  -->
<!-- @ElizavetaMikheeva (Elizaveta Mikheeva) - implemented the front-end (design) of the Products webpage using CSS  -->
@extends('productL')
@section('productP')

@foreach($products as $product)
<div class="laptop_all">
    <img class="image_all_laptop" src="{{ asset($product->image_path) }}">
    <div class="laptop_all_text">
        <a style= "color: inherit" href="{{ route('laptops.show', $product->product_id) }}"> {{$product->laptop_name}} </a>
        <p>{{ $product->processor }}</p>
        <p>{{ $product->GPU }}</p>
    </div>
    <p style="margin-bottom: 42px;">RAM: {{$product->RAM}} GB </p>
    <p class="price" style=" font-weight: bold; margin-bottom: 0px; text-decoration: underline;
    text-decoration:underline; text-decoration-color:aquamarine ">Price: £{{ $product->price }}</p>
    <br>
    <form action='{{route('product.getInfo')}}' method='post' onsubmit='saveScrollPosition(this)'>
        @csrf
        <input type="hidden" name="laptopData" value={{$product->product_id}}>
        <input type="hidden" name="scrollPosition" id="scrollPosition" value="">
        <button class="button_cart_laptop"> Add to Basket </button>
    </form>
    <!--It took me 8 hours of work just to get this thing to get this data and of course it comes out as a string I can't separate... -->
    <!--productToAdd is the string of data that the function returns, what I need to do is get the id from this and use that to update the basket DB -->
</div>

@endforeach
@endsection
