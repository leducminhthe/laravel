@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.notify'))

@section('content')
    @php
        $tab_3 = Request::segment(3);
    @endphp
    <div class="container">
        <style>
            .tab-content .tab-pane{
                box-shadow: none;
                -webkit-box-shadow: none;
            }
            #item-notify-new img,
            #item-notify-viewed img,
            #item-notify-old img{
                max-width: 50px;
            }
        </style>
        <div class="row">
            <div class="col-12 px-0">
                <div class="swiper-container notify-slide pt-1">
                    <div class="swiper-wrapper nav-pills mb-2 text-center" id="nav-tab" role="tablist">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.notify.index') }}', 0)" class="swiper-slide nav-link {{ is_null($tab_3) ? 'active' : '' }}">
                            {{ ucfirst(trans('app.newest')) }}
                        </a>
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.notify.not_seen') }}', 0)" class="swiper-slide nav-link {{ $tab_3 == 'not-seen' ? 'active' : '' }}">
                            {{ data_locale('Chưa xem', 'Not Seen') }}
                        </a>
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.notify.viewed') }}', 0)" class="swiper-slide nav-link {{ $tab_3 == 'viewed' ? 'active' : '' }}">
                            @lang('app.watched')
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="notify_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="list-group list-group-flush">
                            @switch($tab_3)
                                @case('viewed')
                                    @include('themes.mobile.frontend.notify.item_view')
                                    @break
                                @case('not-seen')
                                    @include('themes.mobile.frontend.notify.item_not_seen')
                                    @break
                                @default
                                    @include('themes.mobile.frontend.notify.item_new')
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $('#item-notify-new').on('click', '.btn-delete', function () {
            var ids = $(this).map(function(){return $(this).data('id');}).get();
            Swal.fire({
                title: '',
                text: 'Bạn chắc chắn muốn xoá thông báo này!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ trans("laother.agree") }}!',
                cancelButtonText: '{{ trans("labutton.cancel") }}!',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('module.notify.remove') }}",
                        dataType: 'json',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'ids': ids
                        },
                        success: function (result) {
                            if (result.status === "success") {
                                window.location = '';
                                return false;
                            }
                            else {
                                window.location = '';
                                return false;
                            }
                        }
                    });
                }
            });
        });
        $('#item-notify-viewed').on('click', '.btn-delete', function () {
            var ids = $(this).map(function(){return $(this).data('id');}).get();
            Swal.fire({
                title: '',
                text: 'Bạn chắc chắn muốn xoá thông báo này!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ trans("laother.agree") }}!',
                cancelButtonText: '{{ trans("labutton.cancel") }}!',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('module.notify.remove') }}",
                        dataType: 'json',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'ids': ids
                        },
                        success: function (result) {
                            if (result.status === "success") {
                                window.location = '';
                                return false;
                            }
                            else {
                                window.location = '';
                                return false;
                            }
                        }
                    });
                }
            });
        });
        $('#item-notify-old').on('click', '.btn-delete', function () {
            var ids = $(this).map(function(){return $(this).data('id');}).get();
            Swal.fire({
                title: '',
                text: 'Bạn chắc chắn muốn xoá thông báo này!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ trans("laother.agree") }}!',
                cancelButtonText: '{{ trans("labutton.cancel") }}!',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('module.notify.remove') }}",
                        dataType: 'json',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'ids': ids
                        },
                        success: function (result) {
                            if (result.status === "success") {
                                window.location = '';
                                return false;
                            }
                            else {
                                window.location = '';
                                return false;
                            }
                        }
                    });
                }
            });
        });

        var swiper = new Swiper('.notify-slide', {
            slidesPerView: 3,
            spaceBetween: 0,
        });
    </script>
@endsection
