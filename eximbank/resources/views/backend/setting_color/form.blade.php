@extends('layouts.backend')

@section('page_title', trans('lasetting.button_setting_color'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.button_setting_color'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <form method="post" action="{{ route('backend.setting_color.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        @can('setting-color-create')
                            <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <br>
            <div class="tPanel">
                <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                    <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('lasetting.info') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="base" class="tab-pane active">
                        <div class="row">
                            <div class="col-6">
                                <h3 class="mt-2">1. {{ trans('lasetting.color_menu') }}</h3>
                                <div class="form-group row" id="select_posts">
                                    <div class="col-md-6 control-label">
                                        <label for="content">Background menu</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="color" name="bg_menu" class="avatar avatar-40 shadow-sm bg_menu" value="{{ isset($bg_menu) ? $bg_menu->value : '#FFFFFF' }}"> {{ trans('lasetting.choose_color') }}
                                    </div>
                                </div>
                                <div class="form-group row" id="select_posts">
                                    <div class="col-md-6 control-label">
                                        <label for="content">{{ trans('lasetting.color_text') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="color" name="text_color_menu" class="avatar avatar-40 shadow-sm text_color_menu" value="{{ $color_menu->text }}"> {{ trans('lasetting.choose_color') }}
                                    </div>
                                </div>
                                <div class="form-group row" id="select_posts">
                                    <div class="col-md-6 control-label">
                                        <label for="content">{{ trans('lasetting.color_text_active') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="color" name="text_color_menu_active" class="avatar avatar-40 shadow-sm text_color_menu_active" value="{{ $color_menu->active }}"> {{ trans('lasetting.choose_color') }}
                                    </div>
                                </div>
                                <div class="form-group row" id="select_posts">
                                    <div class="col-md-6 control-label">
                                        <label for="content">{{ trans('lasetting.hover_text_menu') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="color" name="hover_text_color_menu" class="avatar avatar-40 shadow-sm hover_text_color_menu" value="{{ $color_menu->hover_text }}"> {{ trans('lasetting.choose_color') }}
                                    </div>
                                </div>
                                <div class="form-group row" id="select_posts">
                                    <div class="col-md-6 control-label">
                                        <label for="content">{{ trans('lasetting.color_background_menu') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-8">
                                                <input type="color" name="background_menu" class="avatar avatar-40 shadow-sm change-color" value="{{ $color_menu->background }}"> {{ trans('lasetting.choose_color') }}
                                            </div>
                                            <div class="col-4 pl-0">
                                                <input type="color" name="background_menu_child" class="change-color" value="{{ $color_menu->background_child }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" id="select_posts">
                                    <div class="col-md-6 control-label">
                                        <label for="content">{{ trans('lasetting.hover_color_background_menu') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="color" name="hover_background_menu" class="avatar avatar-40 shadow-sm change-hover-color" value="{{ $color_menu->hover_background }}"> {{ trans('lasetting.choose_color') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <h3 class="mt-2">2. {{ trans('lasetting.color_button') }}</h3>
                                <div class="form-group row" id="select_posts">
                                    <div class="col-md-6 control-label">
                                        <label for="content">{{ trans('lasetting.color_text_button') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="color" name="color_text_button" class="avatar avatar-40 shadow-sm change-color-text-button" value="{{ $color_button->text }}"> {{ trans('lasetting.choose_color') }}
                                    </div>
                                </div>
                                <div class="form-group row" id="select_posts">
                                    <div class="col-md-6 control-label">
                                        <label for="content">{{ trans('lasetting.hover_color_text_button') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="color" name="color_hover_text_button" class="avatar avatar-40 shadow-sm change-color-hover-text-button" value="{{ $color_button->hover_text }}"> {{ trans('lasetting.choose_color') }}
                                    </div>
                                </div>
                                <div class="form-group row" id="select_posts">
                                    <div class="col-md-6 control-label">
                                        <label for="content">{{ trans('lasetting.color_btn_click') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="color" name="background_button" class="avatar avatar-40 shadow-sm change-color" value="{{ $color_button->background }}"> {{ trans('lasetting.choose_color') }}
                                    </div>
                                </div>
                                <div class="form-group row" id="select_posts">
                                    <div class="col-md-6 control-label">
                                        <label for="content">{{ trans('lasetting.color_btn_hover') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="color" name="hover_background_button" class="avatar avatar-40 shadow-sm change-hover-color" value="{{ $color_button->hover_background }}"> {{ trans('lasetting.choose_color') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h3 class="mt-2">3. {{ trans('lasetting.color_link') }}</h3>
                                <div class="form-group row" id="select_posts">
                                    <div class="col-md-3 control-label">
                                        <label for="content">{{ trans('lasetting.color_text_link') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="color" name="color_link" class="avatar avatar-40 shadow-sm color_link" value="{{ $color_link->text }}"> {{ trans('lasetting.choose_color') }}
                                    </div>
                                </div>
                                <div class="form-group row" id="select_posts">
                                    <div class="col-md-3 control-label">
                                        <label for="content">{{ trans('lasetting.hover_color_text_link') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="color" name="hover_color_link" class="avatar avatar-40 shadow-sm" value="{{ $color_link->hover_text }}"> {{ trans('lasetting.choose_color') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-12">
                                <h3 class="mt-2">{{ trans('lasetting.color_dashboard') }}</h3>
                                <div class="form-group row" id="select_posts">
                                    <div class="col-md-3 control-label">
                                        <label for="content">{{ trans('lasetting.color_title_dashboard') }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="color" name="color_title" class="avatar avatar-40 shadow-sm change-color-title" value="{{ isset($color_title) ? $color_title->value : '#FFFFFF' }}"> {{ trans('lasetting.choose_color') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h3 class="mt-2">{{ trans('lasetting.color_calendar') }}</h3>
                        <div class="form-group row" id="select_posts">
                            <div class="col-md-3 control-label">
                                <label for="content">{{ trans('lasetting.color_online') }}</label>
                            </div>
                            <div class="col-md-6">
                                <input type="color" name="color_online" class="avatar avatar-40 shadow-sm change-hover-color" value="{{ $color_online ? $color_online->value : '#FFFFFF' }}"> {{ trans('lasetting.choose_color') }}
                                <input type="checkbox" name="i_text_online" id="i_text_online" class="ml-1" value="{{ $i_text_online ? $i_text_online->value : 0 }}" {{ $i_text_online && $i_text_online->value == 1 ? 'checked' : '' }}> <label for="i_text_online">{{ trans('latraining.italic') }}</label>
                                <input type="checkbox" name="b_text_online" id="b_text_online" class="ml-1" value="{{ $b_text_online ? $b_text_online->value : 0 }}" {{ $b_text_online && $b_text_online->value == 1 ? 'checked' : '' }}> <label for="b_text_online">{{ trans('latraining.bold') }}</label>
                            </div>
                        </div>
                        <div class="form-group row" id="select_posts">
                            <div class="col-md-3 control-label">
                                <label for="content">{{ trans('lasetting.color_offline') }}</label>
                            </div>
                            <div class="col-md-6">
                                <input type="color" name="color_offline" class="avatar avatar-40 shadow-sm change-hover-color" value="{{ $color_offline ? $color_offline->value : '#FFFFFF' }}"> {{ trans('lasetting.choose_color') }}
                                <input type="checkbox" name="i_text_offline" id="i_text_offline" class="ml-1" value="{{ $i_text_offline ? $i_text_offline->value : 0 }}" {{ $i_text_offline && $i_text_offline->value == 1 ? 'checked' : '' }}> <label for="i_text_offline">{{ trans('latraining.italic') }}</label>
                                <input type="checkbox" name="b_text_offline" id="b_text_offline" class="ml-1" value="{{ $b_text_offline ? $b_text_offline->value : 0 }}" {{ $b_text_offline && $b_text_offline->value == 1 ? 'checked' : '' }}> <label for="b_text_offline">{{ trans('latraining.bold') }}</label>
                            </div>
                        </div>
                        {{--<div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="title"></label>
                            </div>
                            <div class="col-sm-6">
                                <button id="button_test" type="button" class="btn">{{ trans('labutton.test') }}</button>
                            </div>
                        </div>--}}
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
@section('footer')
    <script type="text/javascript">
        var get_button_color = '{{ $color_button ? $color_button->value : '' }}';
        // var get_hover_button_color = '{{ $hover_color_button ? $hover_color_button->value : '' }}';
        // if(get_button_color) {
        //     $('#button_test').attr('style', 'background: '+ get_button_color +' !important');
        // }
        var golbal_color = '';
        $('.change-color').on('change', function () {
            var set_color = $(this).val();
            golbal_color = set_color;
            $('#button_test').attr('style', 'background: '+ set_color +' !important');
        });
        $('.change-hover-color').on('change', function () {
            if(!golbal_color) {
                golbal_color = get_button_color;
            }
            console.log(golbal_color);
            var set_color = $(this).val();
        });
        $('.change-color-title').on('change', function () {
            var set_color = $(this).val();
            golbal_color = set_color;
            $('#button_test').attr('style', 'background: '+ set_color +' !important');
        });

        $('#i_text_online').on('click', function () {
            if($(this).is(':checked')){
                $('#i_text_online').val(1);
            }else{
                $('#i_text_online').val(0);
            }
        });
        $('#b_text_online').on('click', function () {
            if($(this).is(':checked')){
                $('#b_text_online').val(1);
            }else{
                $('#b_text_online').val(0);
            }
        });
        $('#i_text_offline').on('click', function () {
            if($(this).is(':checked')){
                $('#i_text_offline').val(1);
            }else{
                $('#i_text_offline').val(0);
            }
        });
        $('#b_text_offline').on('click', function () {
            if($(this).is(':checked')){
                $('#b_text_offline').val(1);
            }else{
                $('#b_text_offline').val(0);
            }
        });
    </script>
@endsection
