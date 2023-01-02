@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.history'))

@section('content')
    <div class="container wrraped_training_process">
        <div class="row">
            <div class="col-12">
                <div class="list-group list-group-flush" id="training-process">
                    @if(count($get_history_course) > 0)
                        @foreach($get_history_course as $item)
                            <div class="row mb-2 bg-white shadow border">
                                <div class="col-auto align-self-center">
                                    @if($item->result)
                                        <img src="{{ asset('themes/mobile/img/course_icon.png') }}" alt="" class="avatar-40">
                                    @else
                                        <img src="{{ asset('themes/mobile/img/desktop-pc.png') }}" alt="" class="avatar-40">
                                    @endif
                                </div>

                                <div class="col pl-0 info">
                                    @if ($item->check)
                                    <a href="javascript:void(0);" onclick="loadSpinner('{{ $item->url }}', 0, 3)">
                                    @else
                                    <a href="">
                                    @endif
                                        {{ $item->name }}
                                    </a>
                                    <p class="text-mute mt-1 mb-0">
                                        {{ get_date($item->start_date) }} @if($item->end_date) {{ ' - '. get_date($item->end_date) }} @endif
                                    </p>
                                    <p class="text-mute mt-1 mb-0">
                                        {{ round($item->percent, 2) }}%
                                        <span class="float-right">
                                            {{ $item->type }}
                                        </span>
                                    </p>
                                    <p class="text-mute mt-1">
                                        <span class="{{ $item->result ? 'complete_process' : 'uncomplete_process' }}">
                                            {{ $item->result ? trans('app.completed') : trans('app.uncomplete') }}
                                        </span>
                                        <span class="float-right">
                                            @if ($item->image_cert)
                                            <a href="javascript:void(0)" class="is-completed" data-course_id="{{ $item->course_id }}" data-course_type="{{ $item->course_type }}"> Chứng chỉ <i class="fa fa-sun"></i></a>
                                            @endif
                                        </span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                        <div class="row mt-2">
                            <div class="col-12 px-0 text-right">
                                {{ $get_history_course->links('themes/mobile/layouts/pagination') }}
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-12 text-center">
                                <span>@lang('app.not_found')</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal')
    <div class="modal fade" id="view-cert" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border">
                <div class="modal-body">
                </div>
                <div class="modal-footer text-center">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $('#training-process').on('click', '.studying', function () {
            Swal.fire({
                title: 'Thông báo',
                width: '100%',
                position: 'center',
                html: 'Bạn chưa hoàn thành khóa học <br> <div class="border-bottom pt-5" style="margin: -15px;"></div>',
                focusConfirm: false,
                confirmButtonText: "OK",
            }).then((result) => {
                if (result.value) {
                    return false;
                }
            });
        });

        $('#training-process').on('click', '.is-completed', function () {
            var course_id = $(this).data('course_id');
            var course_type = $(this).data('course_type');

            var currentIcon = $(this).find('i').attr('class');
            $(this).find('i').attr('class', 'fa fa-spinner fa-spin');

            $.ajax({
                type: 'GET',
                url: '/user/trainingprocess/certificate/'+course_id+'/'+course_type+'/{{ profile()->user_id }}',
                dataType: 'json',
                data: {}
            }).done(function (data) {
                $('#training-process .is-completed').find('i').attr('class', currentIcon);

                $('#view-cert .modal-body').html('<img src="'+data.path+'" class="w-100"></img>');
                $('#view-cert .modal-footer').html('<a href="'+data.link_download+'" class="btn bg-template w-100 download-cert"><i class="fa fa-download"></i></a>');
                $('#view-cert').modal();

                return false;
            }).fail(function (data) {
                show_message('Lỗi dữ liệu', 'error');
                $('#training-process .is-completed').find('i').attr('class', currentIcon);

                return false;
            });
        });

        $('#view-cert').on('click', '.download-cert', function(){
            $('#view-cert').modal('hide');
        });
    </script>
@endsection
