@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
    $breadcum = [
        [
            'name' => trans('laprofile.certificates'),
            'url' => '',
        ],
        [
            'name' => trans('lacategory.subject_type'),
            'url' => route('backend.category.subject_type'),
        ],
        [
            'name' => $page_title,
            'url' => '',
        ],
    ];
    @endphp
    @include('layouts.backend.menu_breadcum', $breadcum)
@endsection
@section('header')

@endsection
@section('content')
    <div role="main">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist">
                <li class="nav-item">
                    <a class="nav-link @if ($tabs == 'base' || empty($tabs)) active @endif" href="#base" role="tab" data-toggle="tab">
                        {{ trans('latraining.info') }}
                    </a>
                </li>
                @if ($model->id)
                    <li class="nav-item">
                        <a class="nav-link @if ($tabs == 'object') active @endif" href="#pnobject" role="tab" data-toggle="tab">
                            {{ trans('latraining.certificate_object') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($tabs == 'result') active @endif" href="#pnresult" role="tab" data-toggle="tab">
                            {{ trans('latraining.result') }}
                        </a>
                    </li>
                @endif
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane @if ($tabs == 'base' || empty($tabs)) active @endif">
                    <form id="form_save" method="post" action="{{ route('backend.category.subject_type.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{ $model->id }}">
                        <input type="hidden" name="updated_change" value="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('latraining.subject_type_code') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="code" type="text" class="form-control" value="{{ $model->code }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('latraining.subject_type') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('latraining.time') }}</label> <span class="text-danger">*</span>
                                    </div>
                                    <div class="col-md-9">
                                        <span>
                                            <input name="startdate" type="text"
                                                class="datepicker form-control d-inline-block w-25"
                                                placeholder="{{ trans('latraining.start_date') }}" autocomplete="off"
                                                value="{{ $model->startdate ? get_date($model->startdate) : date('d/m/Y') }}">
                                        </span>
                                        <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                        <span>
                                            <input name="enddate" type="text"
                                                class="datepicker form-control d-inline-block w-25"
                                                placeholder='{{ trans('latraining.end_date') }}' autocomplete="off"
                                                value="{{ $model->enddate ? get_date($model->enddate) : date('t/m/Y') }}">
                                        </span>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('latraining.subject') }}</label> <span class="text-danger">*</span>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="subjects[]" class="form-control load-subject" id="subjects" multiple>
                                            @foreach ($subjects as $item => $value)
                                                <option {{ in_array($value->id, $subjectsSelected) ? 'selected' : '' }} value="{{ $value->id }}">
                                                    {{ $value->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('latraining.certificate') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <select style="width: 65%;" name="certificate_id" id="certificate_id" class="form-control d-inline-block">
                                            <option value="">Chọn</option>
                                            @foreach ($certificate as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $model->certificate_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('latraining.certificate_expiry') }} ({{ trans('lanote.year') }})</label>
                                    </div>
                                    <div class="col-md-4">
                                        <span>
                                            <input name="certificate_expiry" type="number"
                                                class="datepicker_year form-control d-inline-block"
                                                placeholder="{{ trans('latraining.certificate_expiry') }}" autocomplete="off"
                                                value="{{ $model->certificate_expiry ? get_date($model->certificate_expiry) : '' }}">
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('latraining.status') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="radio-inline"><input type="radio" required name="status" value="1" @if ($model->status == 1) checked @endif>
                                            {{ trans('latraining.enable') }}
                                        </label>
                                        <label class="radio-inline"><input type="radio" required name="status" value="0" @if ($model->status == 0) checked @endif>
                                            {{ trans('latraining.disable') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-3"></div>
                                    <div class="col-6">
                                        <div class="btn-group act-btns">
                                            @canany(['category-subject-type-create', 'category-subject-type-edit'])
                                                <button type="button" class="btn" onclick="submitForm()">
                                                    <i class="fa fa-save"></i>&nbsp;{{ trans('labutton.save') }}
                                                </button>
                                            @endcanany
                                            <a href="{{ route('backend.category.subject_type') }}" class="btn">
                                                <i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                @if ($model->id)
                    <div id="pnobject" class="tab-pane @if ($tabs == 'object') active @endif">
                        @include('backend.category.subject_type.object')
                    </div>

                    <div id="pnresult" class="tab-pane @if ($tabs == 'result') active @endif">
                        @include('backend.category.subject_type.result')
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop
@section('footer')
    <script>
        var oldSubjectVal = $("#subjects").val();
        var oldStartDateVal = $('input[name=startdate]').val();
        var oldEndDateVal = $('input[name=enddate]').val();
        var checkIssetResult = '{{ $checkIssetResult }}';

        function submitForm() {
            var id = $('input[name=id]').val();
            if(id && checkIssetResult) {
                var subjects = $("#subjects").val();
                var startDate = $('input[name=startdate]').val();
                var endDate = $('input[name=enddate]').val();

                var formatOldStartDate = new Date(formatDateString(oldStartDateVal));
                var formatOldEndDate = new Date(formatDateString(oldEndDateVal));
                var formatStartDate = new Date(formatDateString(startDate));
                var formatEndDate = new Date(formatDateString(endDate));

                if(oldSubjectVal.sort().join(',') != subjects.sort().join(',') || formatOldStartDate.getTime() != formatStartDate.getTime() || formatOldEndDate.getTime() != formatEndDate.getTime()) {
                    Swal.fire({
                        title: '',
                        text: 'Bạn đã thay đổi chương trình, Kết quả sẽ cập nhật lại ?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ trans("laother.agree") }}!',
                        cancelButtonText: '{{ trans("labutton.cancel") }}!',
                    }).then((result) => {
                        if (result.value) {
                            $('input[name=updated_change]').val(1);
                            $('#form_save').submit();
                        }
                    })
                } else {
                    $('input[name=updated_change]').val('');
                    $('#form_save').submit();
                }
            } else {
                $('#form_save').submit();
            }
        }

        $(".datepicker_year").datepicker({
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years",
            autoclose:true //to close picker once year is selected
        });

        function formatDateString(date) {
            const [day, month, year] = date.split('/');
            return String([year, month, day].join('-'))
        }
    </script>
@endsection
