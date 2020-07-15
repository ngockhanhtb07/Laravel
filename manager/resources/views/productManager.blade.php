<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('css/product.css')}}">
</head>
<body>
<header>
    <div class="navbar">
        <div class="logo">
            <h3>Product Manager</h3>
        </div>
            <form action="{{route('search')}}" method="post" class="form-search">
                <input type="search" name="q" class="form-control search-input" placeholder="Name or Id" autocomplete="off">
            </form>
    </div>
</header>
<main>
    <div class="title">
        <h1>List Products</h1>
    </div>
    <div class="list-product">
        @foreach($Manager as $manager)
            <div class="product-details">
                <div class="image-product">
                    <img src="{{$manager->image}}" alt="">
                </div>
                <div class="title-product">
                    <a href="">{{$manager->name}}</a>
                    <p>{{$manager->price}}</p>
                </div>
{{--               Route Update--}}

{{--                Delete--}}
                <form action="{{route('products.destroy', $manager->id)}}" class="form-destroy" method="post">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}
                    <button type="submit" style="cursor: pointer">
                        <i class="fas fa-minus-circle"></i>
                    </button>
                </form>
            </div>
        @endforeach
            <div class="insert-product">
                <a href="{{ route('products.create') }}">
                    <i class='fas fa-plus-circle'></i>
                </a>
            </div>
    </div>
</main>
<script src='https://kit.fontawesome.com/a076d05399.js'></script>
</body>
</html>
