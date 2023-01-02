@extends('layouts.backend')

@section('page_title', 'Cài đặt điểm giới thiệu')

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.setting'),
                'url' => route('backend.setting')
            ],
            [
                'name' => trans('backend.setting_point'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main">

    <form method="post" action="{{ route('backend.config.refer.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <div class="col-md-5 text-right">
                        <label>{{ trans('backend.referral_score') }}</label>
                    </div>
                    <div class="col-md-5">
                        <input type="number" class="form-control is-number" value="{{$grade_refer}}" name="grade_refer">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-5 text-right">
                        <label>{{ trans('backend.referred_score') }}</label>
                    </div>
                    <div class="col-md-5">
                        <input type="number" class="form-control is-number" value="{{$grade_refered}}" name="grade_refered">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-5 text-right">
                        <label>{{ trans('backend.point_refer_person') }}</label>
                    </div>
                    <div class="col-md-5">
                        <input type="number" class="form-control is-number" value="{{$point_course_referer}}" name="point_course_referer">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-5 text-right">
                        <label>{{ trans('backend.point_introduc') }}</label>
                    </div>
                    <div class="col-md-5">
                        <input type="number" class="form-control is-number" value="{{$point_course_referer_finish}}" name="point_course_referer_finish">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-5 text-right">
                    </div>
                    <div class="col-md-5">

                        <div class="btn-group act-btns">
                            @can('config-point-refer')
                            <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@stop
