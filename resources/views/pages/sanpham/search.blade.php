@extends('welcome')
@section('content')

<div class="features_items"><!--features_items-->
    <h2 class="title text-center">Kết quả Tìm kiếm</h2>
    @foreach($search_product as $key => $all_product)
    <div class="col-sm-4">
        <a href="{{URL::to('chi-tiet-san-pham/'.$all_product->product_id)}}">
        <div class="product-image-wrapper">
            <div class="single-products">
                    <div class="productinfo text-center">
                        <img src="{{URL::to('public/upload/product/'.$all_product->product_image)}}" alt="" />
                        <h2>{{number_format($all_product->product_price).' '. 'VNĐ'}}</h2>
                        <p>{{$all_product->product_name}}</p>
                        <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                    </div>
            </div>
            <div class="choose">
                <ul class="nav nav-pills nav-justified">
                    <li><a href="#"><i class="fa fa-plus-square"></i>Yêu thích</a></li>
                    <li><a href="#"><i class="fa fa-plus-square"></i>So sánh</a></li>
                </ul>
            </div>
        </div>
        </a>
    </div>
    @endforeach
</div><!--features_items-->


@endsection