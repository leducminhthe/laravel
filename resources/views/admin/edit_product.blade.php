@extends('admin_layout')
@section('admin_content')

<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Cấp Nhật Sản Phẩm
                        </header>
                        <div class="panel-body">
                            <?php 
                                $message = Session::get('message');
                                if ($message) {
                                    echo "<span>$message</span>";
                                    Session::put('message',null);
                                }
                            ?>
                            <div class="position-center">
                                @foreach($edit_product as $key => $pro)
                                <form role="form" action="{{URL::to('update-product/'.$pro->product_id)}}" method="post" enctype="multipart/form-data">
                                    {{csrf_field()}}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên Sản phẩm</label>
                                    <input type="text" name="product_name" class="form-control" id="exampleInputEmail1" value="{{ $pro->product_name }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Ảnh Sản phẩm</label>
                                    <input type="file" name="product_image" class="form-control" id="exampleInputEmail1" placeholder="Ảnh sản phẩm">
                                    <img src="{{URL::to('public/upload/product/'. $pro->product_image)}}" alt="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Giá Sản phẩm</label>
                                    <input type="text" name="product_price" class="form-control" id="exampleInputEmail1" value="{{ $pro->product_price }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả sản phẩm</label>
                                    <textarea name="product_desc" class="form-control" id="exampleInputPassword1">{{ $pro->product_desc }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Nội dung sản phẩm</label>
                                    <textarea name="product_content" class="form-control" id="exampleInputPassword1" >{{ $pro->product_content }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Danh mục</label>
                                    <select name="product_cate" class="form-control input-sm m-bot15">
                                        @foreach($cate_product as $key=>$cate)
                                        @if($cate->category_id == $pro->category_id)
                                            <option selected value="{{ $cate->category_id}}">{{ $cate->category_name }}</option>
                                        @else
                                            <option value="{{ $cate->category_id}}">{{ $cate->category_name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Thương hiệu</label>
                                    <select name="product_brand" class="form-control input-sm m-bot15">
                                        @foreach($brand_product as $key=>$brand)
                                        @if($brand->brand_id == $pro->brand_id)
                                            <option selected value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                                        @else
                                            <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Hiển thị</label>
                                    <select name="product_status" class="form-control input-sm m-bot15">
                                        <option value="0">Ẩn</option>
                                        <option value="1">Hiển thị</option>
                                    </select>
                                </div>
                                <div class="checkbox">
                                    
                                </div>
                                <button type="submit" name="add_product" class="btn btn-info">Cấp Nhật Sản Phẩm</button>
                            </form>
                            @endforeach
                            </div>

                        </div>
                    </section>
            </div>
        </div>

@endsection