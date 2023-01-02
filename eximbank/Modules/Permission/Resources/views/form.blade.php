@extends('layouts.backend')

@section('page_title', $action)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('backend.role_management'),
                'url' => route('backend.roles')
            ],
            [
                'name' => $action,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')

<div role="main" id="rolepermission">
    <form method="post" action="{{route('backend.permissions.save',[$permission])}}" class="form-ajax">
    <div class="row">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                <button  class="btn save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;@lang('backend.save')</button>
{{--                <a href="{{ route('module.quiz.user_secondary') }}" class="btn"><i class="fa fa-times-circle"></i> @lang('control.cancel')</a>--}}
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <br>

        <input type="hidden" name="_method" value="PUT">
         <div class="form-group required col-sm-12 required">
             <label>Mã <span class="text-danger">(*)</span></label>
             <input type="text" name="name" value="" class="form-control">
         </div>
        <div class="form-group required col-sm-12 required">
            <label>Tên <span class="text-danger">(*)</span></label>
            <input type="text" name="description" value="" class="form-control">
        </div>
        <div data-init-function="bpFieldInitChecklist" class="form-group col-sm-12" element="div" data-initialized="true">
            <label>Các chức năng</label>
            <input type="hidden" value="[]" name="permissions">
            <div class="row">
                <div class="col-sm-4">
                    <div class="checkbox">
                        <label class="font-weight-normal">
                            <input type="checkbox" value="1"> Thêm
                        </label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="checkbox">
                        <label class="font-weight-normal">
                            <input type="checkbox" value="2"> {{ trans('labutton.edit') }}
                        </label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="checkbox">
                        <label class="font-weight-normal">
                            <input type="checkbox" value="3"> {{ trans('labutton.delete') }}
                        </label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="checkbox">
                        <label class="font-weight-normal">
                            <input type="checkbox" value="4"> Import
                        </label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="checkbox">
                        <label class="font-weight-normal">
                            <input type="checkbox" value="5"> Export
                        </label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="checkbox">
                        <label class="font-weight-normal">
                            <input type="checkbox" value="6"> {{trans('labutton.approve')}}
                        </label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="checkbox">
                        <label class="font-weight-normal">
                            <input type="checkbox" value="7"> {{trans("backend.enable")}}/{{trans("backend.disable")}}
                        </label>
                    </div>
                </div>
            </div>


        </div>
    </form>
</div>
@stop
