
@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.training_evaluation'),
                'url' => ''
            ],
            [
                'name' => 'Mẫu Kế hoạch ứng dụng',
                'url' => route('module.plan_app.template')
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
    <form method="post" action="{{ route('module.plan_app.template.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['plan-app-template-create', 'plan-app-template-edit'])
                    <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.plan_app.template') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.back') }}</a>
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
                        <div class="col-md-9">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.evaluation_form') }}</label><span style="color:red"> * </span>
                                </div>
                                <div class="col-md-9">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}">
                                </div>
                            </div>
                            <div id="wrap-category">
                                <div class="cate-item">
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label category">
                                            <label>{{trans('backend.topic')}} 1</label><span style="color:red"> * </span>
                                        </div>
                                        <div class="col-md-9">
                                            <input name="cate[1]" type="text" class="form-control" placeholder="{{trans('backend.topic')}} 1" value="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                        </div>
                                        <div class="col-md-9">
                                            <div style="font-weight: bold; border-bottom: 1px solid #ccc">{{trans('backend.heading_fields')}} 1</div>
                                            <table class="table table-sm table-borderless border-0">
                                                <thead>
                                                <tr><th style="width: 5%;text-align: center">{{ trans('latraining.stt') }}</th>
                                                    <th style="width: 70%;">{{trans('backend.titles')}}</th>
                                                    <th>{{trans('backend.data_type')}}</th>
                                                </tr></thead>
                                                <tbody>
                                                <tr>
                                                    <td style="text-align: center">1</td>
                                                    <td><input name="item[1][1]" value="" class="form-control" placeholder="Tiêu đề 1"></td>
                                                    <td>
                                                        <select readonly name="type[1][1]"  class="form-control">
                                                            <option value="1">Text</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: center">2</td>
                                                    <td><input name="item[1][2]" value="" class="form-control" placeholder="Tiêu đề 2"></td>
                                                    <td>
                                                        <select name="type[1][2]"  class="form-control">
                                                            <option value="1" selected >Text</option>
                                                            <option value="2" >Int</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: center">3</td>
                                                    <td><input name="item[1][3]" value="" class="form-control" placeholder="Tiêu đề 3"></td>
                                                    <td>
                                                        <select name="type[1][3]"  class="form-control">
                                                            <option value="1" selected >Text</option>
                                                            <option value="2" >Int</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: center">4</td>
                                                    <td><input name="item[1][4]" value="" class="form-control" placeholder="Tiêu đề 4"></td>
                                                    <td>
                                                        <select name="type[1][4]"  class="form-control">
                                                            <option value="1" selected >Text</option>
                                                            <option value="2">Int</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="control-label col-sm-3"></div>
                                <div class="col-md-9">
                                    <button type="button" class="btn btnAdd">{{trans('labutton.add_new')}}</button>
                                    <button type="button" class="btn btnDel">{{trans('labutton.delete')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script id="template" type="text/html">
    <div class="cate-item">
        <div class="form-group row">
            <div class="col-sm-3 control-label category">
                <label>{{trans('backend.topic')}} {index}</label><span style="color:red"> * </span>
            </div>
            <div class="col-md-9">
                <input name="cate[{index}]" type="text" class="form-control" placeholder="{{trans('backend.topic')}} {index}" value="">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">

            </div>
            <div class="col-md-9">
                <div style="font-weight: bold; border-bottom: 1px solid #ccc">Các trường của đề mục <span></span></div>
                <table class="table table-sm table-borderless border-0">
                    <thead>
                    <tr><th style="width: 5%;text-align: center">{{ trans('latraining.stt') }}</th>
                        <th style="width: 70%;">{{trans('backend.titles')}}</th>
                        <th>{{trans('backend.data_type')}}</th>
                    </tr></thead>
                    <tbody>
                    <tr>
                        <td style="text-align: center">1</td>
                        <td><input name="item[{index}][1]" value="" class="form-control" placeholder="Tiêu đề 1"></td>
                        <td>
                            <select readonly name="type[{index}][1]"  class="form-control">
                                <option value="1">Text</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">2</td>
                        <td><input name="item[{index}][2]" value="" class="form-control" placeholder="Tiêu đề 2"></td>
                        <td>
                            <select name="type[{index}][2]"  class="form-control">
                                <option value="1" >Text</option>
                                <option value="2" >Int</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">3</td>
                        <td><input name="item[{index}][3]" value="" class="form-control" placeholder="Tiêu đề 3"></td>
                        <td>
                            <select name="type[{index}][3]"  class="form-control">
                                <option value="1" >Text</option>
                                <option value="2" >Int</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">4</td>
                        <td><input name="item[{index}][4]" value="" class="form-control" placeholder="Tiêu đề 4"></td>
                        <td>
                            <select name="type[{index}][4]"  class="form-control">
                                <option value="1" >Text</option>
                                <option value="2" >Int</option>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</script>

@stop
@section('header')
    <script src="{{asset('/styles/module/planapp/js/plan_app.js')}} "></script>
@endsection
