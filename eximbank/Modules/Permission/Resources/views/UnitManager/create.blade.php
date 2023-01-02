@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('backend.unit_manager_setup'),
                'url' => route('backend.permission.unitmanager')
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
        <form method="post" action="{{ route('backend.permission.unitmanager.save') }}" class="form-validate form-ajax " role="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('lamenu.unit') }} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <select name="unit_id" class="form-control load-unit" data-placeholder="{{trans('backend.choose_unit')}}"></select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.priority') }} 1 <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <select name="priority1[]" id="priority1" multiple class="form-control load-title" data-placeholder="-- {{ trans('backend.choose_title') }} --">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.priority') }} 2</label>
                        </div>
                        <div class="col-md-6">
                            <select name="priority2[]" id="priority2" multiple class="form-control load-title" data-placeholder="-- {{ trans('backend.choose_title') }} --">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.priority') }} 3</label>
                        </div>
                        <div class="col-md-6">
                            <select name="priority3[]" id="priority3" multiple class="form-control load-title" data-placeholder="-- {{ trans('backend.choose_title') }} --">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.priority') }} 4</label>
                        </div>
                        <div class="col-md-6">
                            <select name="priority4[]" id="priority4" multiple class="form-control load-title" data-placeholder="-- {{ trans('backend.choose_title') }} --">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn">{{ trans('labutton.update') }}</button>
                            <a href="{{route('backend.permission.unitmanager')}}" class="btn">{{ trans('labutton.back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
