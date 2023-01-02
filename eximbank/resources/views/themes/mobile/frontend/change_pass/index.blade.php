@extends('themes.mobile.layouts.app')

@section('page_title', 'Đổi mật khẩu')

@section('content')
    <style>
        .noty_change {
            background: #f5f5f5;
        }
        .btn_save_pass {
            border-radius: 20px;
        }
    </style>
    <div class="container">
        <form action="{{ route('themes.mobile.front.change_pass.save') }}" method="post" id="form-change-pass" enctype="multipart/form-data" class="form-ajax">
            <div class="row">
                <div class="col-12 text-center mb-3 py-2 noty_change">
                    <p>Mật khẩu phải gồm chữ thường, số và ký tự đặt biệt. Ít nhất 8 ký tự</p>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-9">
                            <h6 class="mb-1">
                                <b>Mật khẩu hiện tại</b>
                            </h6>
                        </div>
                        <div class="col-3 text-right">
                            <h6 class="mb-1" onclick="showPass()">HIỆN</h6>
                        </div>
                    </div>
                    <input type="password" id="password" name="old_pass" class="form-control mt-2" placeholder="Nhập mật khẩu hiện tại">
                </div>
                <div class="col-12 mt-3">
                    <h6 class="mb-2">
                        <b>Nhập mật khẩu mới</b>
                    </h6>
                    <input type="password" name="new_pass" class="form-control mb-2" placeholder="Nhập mật khẩu mới">
                    <input type="password" name="new_pass_1" class="form-control" placeholder="Nhập lại mật khẩu mới">
                </div>
                <div class="col-12 mt-3 text-center">
                    <button type="submit" class="btn w-50 btn_save_pass">
                        <span>Cập nhật</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('footer')
    <script>        
        const input = document.getElementById("password");
        input.focus();

        function showPass() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
@endsection
