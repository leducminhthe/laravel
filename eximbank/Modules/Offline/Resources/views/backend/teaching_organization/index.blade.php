@extends('layouts.backend')

@section('page_title', trans('latraining.teaching_organization'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training_organizations'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.offline_course'),
                'url' => route('module.offline.management')
            ],
            [
                'name' => $course->name,
                'url' => route('module.offline.edit', ['id' => $course->id])
            ],
            [
                'name' => trans('latraining.teaching_organization'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content_first')
<div class="row mb-2 bg-white pt-2 pb-2" style="box-shadow: 0 5px 20px 0 rgb(0 0 0 / 20%);">
    <div class="col-auto">
        <span class="h5"><b>{{ trans('lacourse.template_rate') }}:</b></span>
    </div>
    <div class="col pl-0">
        <select name="template_rating_teacher_id" id="template_rating_teacher_id" class="form-control select2" data-placeholder="-- {{trans('backend.choose_evaluation_form')}} --">
            @if (isset($offline_teaching_organization_template))
                <option value="{{ $offline_teaching_organization_template->id }}"> {{ $offline_teaching_organization_template->name }}</option>
            @elseif(isset($templates_rating_teacher))
                <option value=""></option>
                @foreach($templates_rating_teacher as $template)
                    <option value="{{ $template->id }}" {{ $course->template_rating_teacher_id == $template->id ? 'selected' : '' }}> {{ $template->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-4 text-right">
        @if ($course->template_rating_teacher_id)
            <a title="Xem mẫu đánh giá" href="{{ route('module.offline.teaching_organization.view_rating_template', [$course->id, $course->template_rating_teacher_id]) }}" class="m-2" target="_bank">
                <i class="fa fa-eye"></i> Xem
            </a>
            <a title="Sửa mẫu đánh giá" href="{{ route('module.rating.template.edit', ['id' => $course->template_rating_teacher_id, 'teaching_organization' => 1]) }}" class="m-2" target="_bank">
                <i class="fa fa-edit"></i> Sửa
            </a>
            <a title="Quét QR đánh giá" href="javascript:void(0)" class="m-2 show-qrcode">
                <i class="fas fa-qrcode"></i> QrCode
            </a>
        @endif
    </div>
</div>
@endsection

@section('content')
<div role="main" class="form_offline_course">
    <div class="row">
        <div class="col-12">
            <h5 class="mb-2"><b>Kết quả đánh giá của học viên</b></h5>
        </div>
        <div class="col-md-8">
            <form class="form-inline form-search mb-3" id="form-search">
                <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('laprofile.enter_code_name_email_username') }}">
                {{--  <div class="w-25">
                    <select name="class_id" id="class_id" class="select2 form-control" data-placeholder="{{ trans('latraining.class_name') }}">
                        <option value=""></option>
                        @foreach ($course_class as $class)
                        <option value="{{ $class->id }}"> {{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>  --}}

                <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
            </form>
        </div>
        <div class="col-md-4 text-right act-btns">
            <a class="btn" href="javascript:void(0);" id="export-rating">
                <i class="fa fa-download"></i> {{ trans('labutton.export') }}
            </a>
        </div>
    </div>
    <table class="tDefault table table-hover" id="table_teaching_organization">
        <thead>
            <tr>
                <th data-field="class_name">{{ trans('latraining.class_name') }}</th>
                <th data-field="code">{{ trans('latraining.employee_code') }}</th>
                <th data-field="full_name">{{ trans('latraining.employee_name') }}</th>
                <th data-field="unit_name">{{ trans('latraining.unit') }}</th>
                <th data-field="title_name">{{ trans('latraining.title') }}</th>
                <th data-field="template_name">{{ trans('latraining.rating_template') }}</th>
                <th data-field="time_send" data-align="center" data-width="10%">{{ trans('latraining.time_rating') }}</th>
                <th data-formatter="view_rating_formatter" data-align="center" data-width="10%">{{ trans('latraining.rating_level_result') }}</th>
            </tr>
        </thead>
    </table>
</div>

{{-- QR CODE --}}
<div class="modal fade" id="modal-qrcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mã cơ cấu tổ chức</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div id="qrcode-wrap">
                        <div id="qrcode">
                            {!! $qrcode !!}
                        </div>
                        <p>Quét mã để vào cơ cấu tổ chức</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click','.show-qrcode',function () {
        $("#modal-qrcode").modal();
    });

    var template_rating_teacher_id = '{{ $course->template_rating_teacher_id }}';
    if(template_rating_teacher_id) {
        $('#template_rating_teacher_id').prop('disabled', true);
    }

    $('#template_rating_teacher_id').on('change', function(){
        var template_rating_teacher_id = $(this).val();

        $('#template_rating_teacher_id').prop('disabled', true);

        $.ajax({
            url: '{{ route('module.offline.teaching_organization.update_template_rating_teacher', [$course->id]) }}',
            type: 'POST',
            data: {
                template_rating_teacher_id: template_rating_teacher_id,
            },
        }).done(function(data) {

            show_message(data.message, data.status);

            if(data.redirect){
                window.location = data.redirect;
            }

            return false;
        }).fail(function(data) {
            show_message(
                'Lỗi hệ thống',
                'error'
            );
            return false;
        });
    });

    function view_rating_formatter(value, row, index){
        return '<a href="'+row.view_rating+'" class="btn"><i class="fa fa-eye"></i></a>';
    }
    var table = new LoadBootstrapTable({
        url: '{{ route('module.offline.teaching_organization.getData', ['course_id' => $course->id]) }}',
        table: "#table_teaching_organization"
    });

    $('#export-rating').on('click', function(){
        let form_search = $("#form-search").serialize();
        window.location = '{{ route('module.offline.teaching_organization.export', ['course_id' => $course->id]) }}?'+form_search;
    });
</script>
@endsection
