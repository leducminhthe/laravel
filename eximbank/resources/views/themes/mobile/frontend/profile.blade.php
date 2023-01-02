@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.account'))

@section('content')
    <div class="wrapped_info">
        <div class="container">
            <div class="card card_info mt-1 mb-1">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-1 text-center">
                            <figure class="avatar avatar-60">
                                <a href="javascript:void(0)" class="" @if (!userThird()) data-toggle="modal" data-target="#modalInfoUser" @endif>
                                    <img src="{{ \App\Models\Profile::avatar() }}" alt="" class="avatar-60">
                                </a>
                            </figure>
                        </div>
                        <div class="col-12 text-center">
                            <p class="mb-1" @if (!userThird()) data-toggle="modal" data-target="#modalInfoUser" @endif>
                                {{ \App\Models\Profile::fullname() }}
                            </p>
                            <p class="text-mute mb-1">{{ \App\Models\Profile::usercode() }}</p>
                            <div>
                                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.front.promotion') }}', 1, 3)">
                                    <p class="mb-1 score_user">
                                        <span class="mr-1">{{ $user_point ? $user_point->point.' '.trans('lasurvey.reward_points') : '' }} </span>
                                        <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                                        <i class="material-icons">navigate_next</i>
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8 pr-0">
                            <div class="img_qr_code" onclick="showModalQR()">
                                <img src="{{ asset('themes/mobile/img/qrcode-user.png') }}" alt="" class="avatar-40">
                            </div>
                        </div>
                        <div class="col-4 pl-0 text-center">
                            <span class="font-weight-bold">Điểm xếp hạng</span> <br>
                            <b>{{ $user_rank .'/'. $total_user->count() }}</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- LỘ TRÌNH ĐÀO TẠO --}}
        <div class="container my-2">
            <div class="row m-0 ">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('module.frontend.training_by_title') }}', 1, 3)" class="d_flex_align w-100">
                    <div class="col-10 pl-0 d_flex_align">
                        <div class="icon_menu_user">
                            <div class="icon" style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-160.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-backend/svgexport-160.svg') }}) no-repeat;
                                -webkit-mask-size: 45px 23px;">
                            </div>
                        </div>
                        <h6 class="ml-2 mb-0">{{ data_locale('Lộ trình đào tạo', 'Training Roadmap') }}</h6>
                    </div>
                    <div class="col-2 text-right pr-0">
                        <i class="material-icons">navigate_next</i>
                    </div>
                </a>
            </div>
        </div>

        {{-- TOP HỌC VIÊN CÓ ĐIỂM CAO --}}
        {{--  <div class="container my-2">
            <div class="row m-0 ">
                <a href="{{ route('themes.mobile.front.list_hight_score') }}" class="d_flex_align w-100">
                    <div class="col-10 pl-0 d_flex_align">
                        <div class="icon_menu_user">
                            <div class="icon" style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-56.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-backend/svgexport-56.svg') }}) no-repeat;
                                -webkit-mask-size: 45px 23px;">
                            </div>
                        </div>
                        <h6 class="ml-2 mb-0">@lang('app.top_students_high_scores')</h6>
                    </div>
                    <div class="col-2 text-right pr-0">
                        <i class="material-icons">navigate_next</i>
                    </div>
                </a>
            </div>
        </div>  --}}

        {{-- LỊCH SỬ ĐIỂM THƯỞNG --}}
        <div class="container my-2">
            <div class="row m-0 ">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.front.history_point') }}', 1, 3)" class="d_flex_align w-100">
                    <div class="col-10 pl-0 d_flex_align">
                        <div class="icon_menu_user">
                            <div class="icon" style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-52.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-backend/svgexport-52.svg') }}) no-repeat;
                                -webkit-mask-size: 45px 23px;">
                            </div>
                        </div>
                        <h6 class="ml-2 mb-0">@lang('app.point_accumulation_history')</h6>
                    </div>
                    <div class="col-2 text-right pr-0">
                        <i class="material-icons">navigate_next</i>
                    </div>
                </a>
            </div>
        </div>
        {{-- HUY HIỆU THI ĐUA --}}
        <div class="container my-2">
            <div class="row m-0 ">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.front.emulation_badge_list') }}',1 , 3)" class="d_flex_align w-100">
                    <div class="col-10 pl-0 d_flex_align">
                        <div class="icon_menu_user">
                            <div class="icon" style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-148.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-backend/svgexport-148.svg') }}) no-repeat;
                                -webkit-mask-size: 45px 23px;">
                            </div>
                        </div>
                        <h6 class="ml-2 mb-0">{{ trans('lamenu.compete_title') }}</h6>
                    </div>
                    <div class="col-2 text-right pr-0">
                        <i class="material-icons">navigate_next</i>
                    </div>
                </a>
            </div>
        </div>
        {{-- Khoá học yêu thích--}}
        <div class="container my-2">
            <div class="row m-0 ">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.my_course_like') }}', 1, 3)" class="d_flex_align w-100">
                    <div class="col-10 pl-0 d_flex_align">
                        <div class="icon_menu_user">
                            <div class="icon" style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-8.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-backend/svgexport-8.svg') }}) no-repeat;
                                -webkit-mask-size: 45px 23px;">
                            </div>
                        </div>
                        <h6 class="ml-2 mb-0">{{ data_locale('Khoá học yêu thích', 'Favorite course') }}</h6>
                    </div>
                    <div class="col-2 text-right pr-0">
                        <i class="material-icons">navigate_next</i>
                    </div>
                </a>
            </div>
        </div>
        {{-- KẾT QUẢ THI--}}
        <div class="container my-2">
            <div class="row m-0 ">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.front.quiz_result', ['user_id' => $user->user_id]) }}', 1, 3)" class="d_flex_align w-100">
                    <div class="col-10 pl-0 d_flex_align">
                        <div class="icon_menu_user">
                            <div class="icon" style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-32.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-backend/svgexport-32.svg') }}) no-repeat;
                                -webkit-mask-size: 45px 23px;">
                            </div>
                        </div>
                        <h6 class="ml-2 mb-0">{{ data_locale('Kết quả thi', 'Quiz Result') }}</h6>
                    </div>
                    <div class="col-2 text-right pr-0">
                        <i class="material-icons">navigate_next</i>
                    </div>
                </a>
            </div>
        </div>
        {{-- LỊCH SỬ HỌC--}}
        <div class="container my-2">
            <div class="row m-0 ">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.training_process') }}', 1, 3)" class="d_flex_align w-100">
                    <div class="col-10 pl-0 d_flex_align">
                        <div class="icon_menu_user">
                            <div class="icon" style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-162.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-backend/svgexport-162.svg') }}) no-repeat;
                                -webkit-mask-size: 45px 23px;">
                            </div>
                        </div>
                        <h6 class="ml-2 mb-0">@lang('app.learning_history')</h6>
                    </div>
                    <div class="col-2 text-right pr-0">
                        <i class="material-icons">navigate_next</i>
                    </div>
                </a>
            </div>
        </div>
        {{-- LỊCH SỬ Quà tặng--}}
        <div class="container my-2">
            <div class="row m-0 ">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.my_promotion') }}', 1, 3)" class="d_flex_align w-100">
                    <div class="col-10 pl-0 d_flex_align">
                        <div class="icon_menu_user">
                            <div class="icon" style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-49.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-backend/svgexport-49.svg') }}) no-repeat;
                                -webkit-mask-size: 45px 23px;">
                            </div>
                        </div>
                        <h6 class="ml-2 mb-0">@lang('backend.promotion_history')</h6>
                    </div>
                    <div class="col-2 text-right pr-0">
                        <i class="material-icons">navigate_next</i>
                    </div>
                </a>
            </div>
        </div>

        {{-- CHỨNG CHỈ BÊN NGOÀI --}}
        <div class="container my-2">
            <div class="row m-0 ">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.front.my_certificate') }}', 1, 3)" class="d_flex_align w-100">
                    <div class="col-10 pl-0 d_flex_align">
                        <div class="icon_menu_user">
                            <div class="icon" style="-webkit-mask: url({{ asset('images/svg-backend/svgexport-19.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-backend/svgexport-19.svg') }}) no-repeat;
                                -webkit-mask-size: 45px 23px;">
                            </div>
                        </div>
                        <h6 class="ml-2 mb-0">Chứng chỉ bên ngoài</h6>
                    </div>
                    <div class="col-2 text-right pr-0">
                        <i class="material-icons">navigate_next</i>
                    </div>
                </a>
            </div>
        </div>

        {{-- HƯỚNG DẪN --}}
        <div class="container my-2">
            <div class="row m-0 ">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.guide') }}', 1, 3)" class="d_flex_align w-100">
                    <div class="col-10 pl-0 d_flex_align">
                        <div class="icon_menu_user">
                            <div class="icon" style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-37.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-frontend/svgexport-37.svg') }}) no-repeat;
                                -webkit-mask-size: 45px 23px;">
                            </div>
                        </div>
                        <h6 class="ml-2 mb-0">@lang('app.guide')</h6>
                    </div>
                    <div class="col-2 text-right pr-0">
                        <i class="material-icons">navigate_next</i>
                    </div>
                </a>
            </div>
        </div>

        {{-- CÂU HỎI THƯỜNG GẶP --}}
        <div class="container my-2">
            <div class="row m-0 ">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.faq.frontend.index') }}', 1, 3)" class="d_flex_align w-100">
                    <div class="col-10 pl-0 d_flex_align">
                        <div class="icon_menu_user">
                            <div class="icon" style="-webkit-mask: url({{ asset('images/svg-frontend/svgexport-41.svg') }}) no-repeat;
                                mask: url({{ asset('images/svg-frontend/svgexport-41.svg') }}) no-repeat;
                                -webkit-mask-size: 45px 23px;">
                            </div>
                        </div>
                        <h6 class="ml-2 mb-0">@lang('app.faq')</h6>
                    </div>
                    <div class="col-2 text-right pr-0">
                        <i class="material-icons">navigate_next</i>
                    </div>
                </a>
            </div>
        </div>

        @if (userThird())
            {{-- XOÁ TÀI KHOẢN--}}
            <div class="container my-2">
                <div class="row m-0 ">
                    <a href="javascript:void(0);" class="d_flex_align w-100" id="clear_account_user_third">
                        <div class="col-10 pl-0 d_flex_align">
                            <div class="icon_menu_user">
                                <div class="icon" style="-webkit-mask: url({{ asset('images/svg-backend/trash.svg') }}) no-repeat center;
                                    mask: url({{ asset('images/svg-backend/trash.svg') }}) no-repeat center;
                                    -webkit-mask-size: 40px 27px;">
                                </div>
                            </div>
                            <h6 class="ml-2 mb-0">Xoá tài khoản</h6>
                        </div>
                        <div class="col-2 text-right pr-0">
                            <i class="material-icons">navigate_next</i>
                        </div>
                    </a>
                </div>
            </div>
        @endif

        {{-- ĐĂNG XUẤT--}}
        <div class="container my-3">
            <div class="row m-0">
                <div class="col-12 text-center">
                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('logout') }}', 1, 3)" class="btn w-100 link_btn">
                        @lang('app.logout')
                    </a>
                </div>
            </div>
        </div>

        <div class="container my-3">
            <div class="row m-0">
                <div class="col-12 text-center">
                    Version {{ session()->get('VersionCode') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div id="modal-change-info" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('module.frontend.user.change_info') }}" method="post" id="form-change-info" enctype="multipart/form-data" class="form-ajax">
                @csrf
                <input type="hidden" name="key" value="">
                <input type="hidden" name="value_old" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Đổi thông tin</h6>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-3 control-label">
                                <label for="value_new">Giá trị cũ</label>
                            </div>
                            <div class="col-md-8" id="value-old"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 control-label">
                                <label for="value_new">Giá trị thay đổi</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="value_new" id="value-new" type="text" class="form-control" placeholder="Giá trị thay đổi" autocomplete="off" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 control-label">
                                <label for="value_new">{{ trans('lasetting.note') }}</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="note" id="note" type="text" class="form-control" placeholder="{{ trans('lasetting.note') }}" autocomplete="off"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn">{{ trans('lacore.save') }}</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL QR CODE --}}
    <div id="modal-qr-code" class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">{{ trans('app.your_QR_code') }}</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="qr_code text-center">
                        {!! QrCode::size(250)->generate($info_qrcode) !!}
                    </div>
                    <p class="text-center mt-2">@lang('app.notify_your_qr_code')</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        var swiper = new Swiper('.profile-slide', {
            slidesPerView: 'auto',
            spaceBetween: 0,
            loop: true,
            autoplay: {
                delay: 1500,
                disableOnInteraction: false,
            },
        });

        $('#info-user').on('click', function() {
            $('#modalInfoUser').modal();
        });

        $('.change-info').on('click', function (event) {
            event.preventDefault();
            var key = $(this).data('key');
            var value_old = $(this).data('value-old');

            $('#modal-change-info #value-old').text(value_old).trigger('change');
            $('#modal-change-info input[name=value_old]').val(value_old).trigger('change');
            $('#modal-change-info input[name=key]').val(key).trigger('change');

            $('#modalInfoUser .close').trigger('click');
            $('#modal-change-info').modal();
            return false;
        });

        function showModalQR() {
            $('#modal-qr-code').modal()
        }

        $('#clear_account_user_third').on('click', function() {
            Swal.fire({
                title: 'Bạn chắc muốn xoá?',
                type: 'warning',
                html: 'Tài khoản của bạn sẽ xoá khỏi ứng dụng!',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                focusConfirm: false,
                confirmButtonText: "Có",
                cancelButtonText: "Không",
            }).then((result) => {
                if (result.value) {

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('themes.mobile.frontend.remove_account') }}',
                        dataType: 'json',
                        data: {}
                    }).done(function (data) {
                        show_message(data.message, data.status);

                        window.location = data.redirect;

                    }).fail(function (data) {
                        show_message('Không thể xoá tài khoản của bạn', 'error');
                    });

                    return false;
                }
            });
        });
    </script>
@endsection
