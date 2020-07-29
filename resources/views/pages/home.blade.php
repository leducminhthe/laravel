@extends('welcome')
@section('content')

<div class="features_items"><!--features_items-->
    <h2 class="title text-center">Sản phẩm mới</h2>
    <div>
        <p>Tìm thấy {{count($all_product)}} sản phẩm</p>
    </div>
    @foreach($all_product as $key => $product)
    <div class="col-sm-4">
        <a href="{{URL::to('chi-tiet-san-pham/'.$product->product_id)}}" >
            <div class="product-image-wrapper">
                <div class="single-products">
                        <div class="productinfo text-center">
                            <img src="{{URL::to('public/upload/product/'.$product->product_image)}}" alt="" />
                            <h2>{{number_format($product->product_price).' '. 'VNĐ'}}</h2>
                            <p>{{$product->product_name}}</p>
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
            <div>
                
            </div>
        </a>
    </div>
    @endforeach
</div><!--features_items-->
{{ $all_product->links() }}
@endsection