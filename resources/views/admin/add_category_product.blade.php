@extends('admin_layout')
@section('admin_content')

<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Thêm Danh Mục Sản Phẩm
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
                                <form role="form" action="{{URL::to('save-category-product')}}" method="post" id="fromcate">
                                    {{csrf_field()}}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên Danh Mục</label>
                                    <input type="text" name="category_product_name" class="form-control" id="exampleInputEmail1" placeholder="Tên Danh Mục">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả danh mục</label>
                                    <textarea name="category_product_desc" class="form-control" id="exampleInputPassword1" placeholder="Mô tả danh mục"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Từ khóa danh mục</label>
                                    <textarea name="category_product_keywords" class="form-control" id="exampleInputPassword1" placeholder="Từ khóa danh mục"></textarea>
                                </div>
                                <div class="form-group">
                                    <select name="category_product_status" class="form-control input-sm m-bot15">
                                        <option value="0">Ẩn</option>
                                        <option value="1">Hiển thị</option>
                                    </select>
                                </div>
                                <div class="checkbox">
                                    
                                </div>
                                <button type="submit" name="add_category_product" class="btn btn-info">Thêm Danh Mục</button>
                            </form>
                            </div>

                        </div>
                    </section>
            </div>
        </div>

@endsection