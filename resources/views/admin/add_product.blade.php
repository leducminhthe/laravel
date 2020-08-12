@extends('admin_layout')
@section('admin_content')

<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Thêm Sản Phẩm
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
                                <form role="form" action="{{URL::to('save-product')}}" method="post" enctype="multipart/form-data" id="formDemo">
                                    {{csrf_field()}}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên Sản phẩm</label>
                                    <input type="text" name="product_name" class="form-control" id="name" placeholder="Tên Danh Mục">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Ảnh Sản phẩm</label>
                                    <input type="file" name="product_image" class="form-control" id="exampleInputEmail1" placeholder="Ảnh sản phẩm">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Giá Sản phẩm</label>
                                    <input type="text" name="product_price" class="form-control" placeholder="Giá sản phẩm">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả sản phẩm</label>
                                    <textarea name="product_desc" class="form-control" id="ckeditor2" placeholder="Mô tả sản phẩm"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Nội dung sản phẩm</label>
                                    <textarea name="product_content" class="form-control" id="ckeditor1" placeholder="Nội dung sản phẩm"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Danh mục</label>
                                    <select name="product_cate" class="form-control input-sm m-bot15">
                                        @foreach($cate_product as $key=>$cate)
                                        <option value="{{ $cate->category_id}}">{{ $cate->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Thương hiệu</label>
                                    <select name="product_brand" class="form-control input-sm m-bot15">
                                        @foreach($brand_product as $key=>$brand)
                                        <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
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
                                <button type="submit" name="add_product" class="btn btn-info">Thêm Sản Phẩm</button>
                            </form>
                            </div>

                        </div>
                    </section>
            </div>
        </div>

@endsection