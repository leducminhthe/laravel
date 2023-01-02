@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.category'),
                'url' => route('backend.category')
            ],
            [
                'name' => trans('backend.unit_type'),
                'url' => route('backend.category.unit_type')
            ],
            [
                'name' => $page_title,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main">
    <form method="post" action="{{ route('backend.category.unit_type.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['category-unit-type-create', 'category-unit-type-edit'])
                        <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endcanany
                    <a href="{{ route('backend.category.unit_type') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
        </div>

        <div class="clear"></div>

        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('latraining.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.unit_type') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('lacategory.unit_code') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="code" type="text" class="form-control" value="" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('lacategory.unit_code') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="all_unit_type_code">
                                        @if ( !empty($units_type_code) )
                                            @foreach ($units_type_code as $unit_type_code)
                                                <span class="unit_type_code">
                                                    {{ $unit_type_code->code }}
                                                    <span class="delete_code" onclick="deleteUnitCode({{ $unit_type_code->id }})">x</span>
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    function deleteUnitCode(id) {
        $.ajax({
            url: '{{ route('backend.category.unit_type.remove') }}',
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            show_message(data.message, data.status);
            window.location = '';
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    }
</script>
@stop
