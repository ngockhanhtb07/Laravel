<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Insert</title>
    <link rel="stylesheet" href="{{asset('css/product.css')}}">
</head>
<body>
<main>
    <div class="main-product">
        <h3>Insert Data</h3>
{{--        form nhap --}}
        <form action="{{route('products.store')}}" method="post" class="form-control">
            {{csrf_field()}}

            Name:
            <input type="text" name="name">
            image:
            <input type="text" name="image">
            price:
            <input type="text" name="price">
            <input type="submit" value="submit" name="submit">
        </form>
    </div>
</main>
</body>
</html>