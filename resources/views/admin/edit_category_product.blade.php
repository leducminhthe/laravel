@extends('admin_layout')
@section('admin_content')

<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Cấp Nhật Danh Mục Sản Phẩm
                        </header>
                        <div class="panel-body">
                            <?php 
                                $message = Session::get('message');
                                if ($message) {
                                    echo "<span>$message</span>";
                                    Session::put('message',null);
                                }
                            ?>
                            @foreach($edit_category_product as $key=> $edit_value)
                            <div class="position-center">
                                <form role="form" action="{{URL::to('update-category-product/'.$edit_value->category_id)}}" method="post">
                                    {{csrf_field()}}
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Tên Danh Mục</label>
                                        <input type="text" value="{{$edit_value->category_name}}" name="category_product_name" class="form-control" id="exampleInputEmail1">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Mô tả danh mục</label>
                                        <textarea name="category_product_desc" class="form-control" id="exampleInputPassword1" placeholder="Mô tả danh mục">{{$edit_value->category_desc}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Từ khóa danh mục</label>
                                        <textarea name="category_product_keywords" class="form-control" id="exampleInputPassword1" placeholder="Từ khóa danh mục">{{$edit_value->meta_keywords}}</textarea>
                                    </div>
                                    <div class="checkbox">
                                        
                                    </div>
                                    <button type="submit" name="update_category_product" class="btn btn-info">Cập Nhật Danh Mục</button>
                                </form>
                            </div>
                            @endforeach

                        </div>
                    </section>
            </div>
        </div>

@endsection