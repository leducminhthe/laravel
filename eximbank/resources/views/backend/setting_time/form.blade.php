@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lasetting.setting_time'),
                'url' => route('backend.setting_time')
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
<form method="post" action="{{ route('backend.setting_time.save') }}" class="form-ajax" role="form" enctype="multipart/form-data">
    <div role="main" class="form_setting_time">
            <input type="hidden" name="id" value="{{ $settingTimeObject->id }}">
            <div class="row">
                <div class="col-md-6">
                </div>
                <div class="col-md-6 text-right">
                    <div class="btn-group act-btns">
                        <button type="button" class="btn" data-toggle="modal" data-target="#exampleModal">
                            {{ trans('laother.more_languages') }}
                        </button>
                    </div>
                    <div class="btn-group act-btns">
                        @can(['setting-time-create', 'setting-time-edit'])
                            <button type="submit" class="btn btn_save_time" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                        @endcan
                        <button type="button" class="btn" onclick="addSession()"><i class="fa fa-plus-circle"></i> &nbsp; {{ trans('labutton.add_new') }}</button>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <br>
            <div class="tPanel">
                <div class="mb-3">
                    <span class="text-danger">{{ trans('lasetting.notify_setting_time') }}</span>
                </div>
                <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                    <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('lasetting.info') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="base" class="tab-pane active">
                        <div class="all_session">
                            @if ($settingTimeObject->id)
                                @foreach ($get_setting_times as $key => $item)
                                    <div class="form-group row" id="session_{{ $key + 1 }}">
                                        <div class="col-md-1 pr-0 control-label session">
                                            <label for="content"> {{ trans('latraining.session').' '.($key + 1) }}:</label>
                                        </div>
                                        <div class="col-11">
                                            <div class="row wrraper_item">
                                                <div class="col-md-7">
                                                    <div class="row m-0">
                                                        <div class="col-8 pl-0 pr-1 input_text input_text_{{ $key + 1 }}">
                                                            @foreach ($item->value as $value)
                                                                <input type="text" name="value_{{ $key + 1 }}_{{ $value->languages }}" class="input_emoji mt-1" value="{{ $value->value }}">
                                                            @endforeach
                                                        </div>
                                                        <div class="col-4 pr-0 pl-1 d_flex_align">
                                                            <input name="time_1[]" type="text" class="form-control timepicker time_1" onblur="checkTime({{ $key + 1 }})" required value="{{ $item->start_time }}">
                                                            <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                                            <input name="time_2[]" type="text" class="form-control timepicker time_2" onblur="checkTime({{ $key + 1 }})" required value="{{ $item->end_time }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 p-0">
                                                    <input type="color" name="color_text[]" value="{{ $item->color_text ? $item->color_text : 'fff' }}"> {{ trans('lasetting.choose_color') }}
                                                    <input type="checkbox" name="i_text_{{ $key + 1 }}" id="i_text_{{ $key + 1 }}" class="ml-1" onclick="iChecked({{ $key + 1 }})" value="{{ $item->i_text ? $item->i_text : 1 }}" {{ $item->i_text && $item->i_text == 1 ? 'checked' : '' }}> 
                                                    <label for="i_text_{{ $key + 1 }}">{{ trans('latraining.italic') }}</label>
                                                    <input type="checkbox" name="b_text_{{ $key + 1 }}" id="b_text_{{ $key + 1 }}" class="ml-1 b_checked" onclick="bChecked({{ $key + 1 }})" value="{{ $item->b_text ? $item->b_text : 1 }}" {{ $item->b_text && $item->b_text == 1 ? 'checked' : '' }}> 
                                                    <label for="b_text_{{ $key + 1 }}">{{ trans('latraining.bold') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="form-group row" id="session_1">
                                    <div class="col-md-1 pr-0 control-label session">
                                        <label for="content">{{ trans('latraining.session') }} 1:</label>
                                    </div>
                                    <div class="col-11">
                                        <div class="row wrraper_item">
                                            <div class="col-md-7">
                                                <div class="row m-0">
                                                    <div class="col-8 pl-0 pr-1 input_text input_text_1">
                                                        <input type="text" name="value_1_vi" class="input_emoji" value="{{ trans('lasetting.good_morning') }} {Name}">
                                                    </div>
                                                    <div class="col-4 pr-0 pl-1 d_flex_align">
                                                        <input name="time_1[]" type="text" class="form-control timepicker time_1" onblur="checkTime(1)" placeholder="{{ trans('laother.choose_start_time') }}" required value="05:00:00">
                                                        <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                                        <input name="time_2[]" type="text" class="form-control timepicker time_2" onblur="checkTime(1)" placeholder="{{ trans('laother.choose_end_time') }}" required value="11:00:00">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 p-0">
                                                <input type="color" name="color_text[]" class="avatar avatar-40 shadow-sm change-hover-color"> {{ trans('lasetting.choose_color') }}
                                                <input type="checkbox" name="i_text_1" id="i_text_1" class="ml-1" onclick="iChecked(1)" value="0"> <label for="i_text_1">{{ trans('latraining.italic') }}</label>
                                                <input type="checkbox" name="b_text_1" id="b_text_1" class="ml-1 b_checked" onclick="bChecked(1)" value="0"> <label for="b_text_1">{{ trans('latraining.bold') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" id="session_2">
                                    <div class="col-md-1 pr-0 control-label session">
                                        <label for="content">{{ trans('latraining.session') }} 2:</label>
                                    </div>
                                    <div class="col-11">
                                        <div class="row wrraper_item">
                                            <div class="col-md-7">
                                                <div class="row m-0">
                                                    <div class="col-8 pl-0 pr-1 input_text input_text_2">
                                                        <input type="text" name="value_2_vi" class="input_emoji" value="{{ trans('lasetting.good_afternoon') }} {Name}">
                                                    </div>
                                                    <div class="col-4 pr-0 pl-1 d_flex_align">
                                                        <input name="time_1[]" type="text" class="form-control timepicker time_1" onblur="checkTime(2)" placeholder="{{ trans('laother.choose_start_time') }}" required value="11:01:00">
                                                        <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                                        <input name="time_2[]" type="text" class="form-control timepicker time_2" onblur="checkTime(2)" placeholder="{{ trans('laother.choose_end_time') }}" required value="13:00:00">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 p-0">
                                                <input type="color" name="color_text[]" class="avatar avatar-40 shadow-sm change-hover-color"> {{ trans('lasetting.choose_color') }}
                                                <input type="checkbox" name="i_text_2" id="i_text_2" class="ml-1" onclick="iChecked(2)" value="0"> <label for="i_text_2">{{ trans('latraining.italic') }}</label>
                                                <input type="checkbox" name="b_text_2" id="b_text_2" class="ml-1 b_checked" onclick="bChecked(2)" value="0"> <label for="b_text_2">{{ trans('latraining.bold') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" id="session_3">
                                    <div class="col-md-1 pr-0 control-label session">
                                        <label for="content">{{ trans('latraining.session') }} 3:</label>
                                    </div>
                                    <div class="col-11">
                                        <div class="row wrraper_item">
                                            <div class="col-md-7">
                                                <div class="row m-0">
                                                    <div class="col-8 pl-0 pr-1 input_text input_text_3">
                                                        <input type="text" name="value_3_vi" class="input_emoji" value="{{ trans('lasetting.see_you_again') }} {Name}">
                                                    </div>
                                                    <div class="col-4 pr-0 pl-1 d_flex_align">
                                                        <input name="time_1[]" type="text" class="form-control timepicker time_1" onblur="checkTime(3)" placeholder="{{ trans('laother.choose_start_time') }}" required value="13:01:00">
                                                        <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                                        <input name="time_2[]" type="text" class="form-control timepicker time_2" onblur="checkTime(3)" placeholder="{{ trans('laother.choose_end_time') }}" required value="18:00:00">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 p-0">
                                                <input type="color" name="color_text[]" class="avatar avatar-40 shadow-sm change-hover-color"> {{ trans('lasetting.choose_color') }}
                                                <input type="checkbox" name="i_text_3" id="i_text_3" class="ml-1" onclick="iChecked(3)" value="0"> <label for="i_text_3">{{ trans('latraining.italic') }}</label>
                                                <input type="checkbox" name="b_text_3" id="b_text_3" class="ml-1 b_checked" onclick="bChecked(3)" value="0"> <label for="b_text_3">{{ trans('latraining.bold') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="form-group row">
                            <div class="col-md-1 pr-0 control-label">
                                <label for="object">{{ trans('lasetting.object') }} </label>
                            </div>
                            <div class="col-11">
                                <div class="row">
                                    <div class="col-md-7">
                                        <select name="object[]" id="object" class="form-control select2" data-placeholder="-- {{ trans('lasetting.object') }} --" multiple>
                                            <option></option>
                                            @foreach($unit as $item)
                                                <option value="{{ $item->id }}" {{ !empty($get_object) && in_array($item->id, $get_object) ? 'selected' : '' }}> {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans('laother.more_languages') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @foreach ($languagesType as $language)
                        <label>
                            <input type='checkbox' 
                                {{ !empty($get_languages) && in_array($language->key, $get_languages) ? 'checked disabled' : '' }}
                                id="{{ $language->key }}" 
                                name="key_language[]"  
                                onclick="addLanguage('{{ $language->key }}')" 
                                value="{{ $language->key }}"
                            >
                            {{ $language->name }}
                        </label>
                        <br>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</form>
    <script>
        function addLanguage(key) {
            if (document.getElementById(key).checked) {
                let countInput = $('.input_text').length
                for (let index = 1; index <= countInput; index++) {
                    console.log(index);
                    let input = `<input type="text" name="value_`+ index +`_`+ key +`" class="mt-1 input_emoji input_emoji_`+ key +`" value="{Name}" required>`
                    $('.input_text_'+ index).append(input)
                }
                $(".input_emoji_"+key).emojioneArea({
                    pickerPosition: "bottom",
                    hidePickerOnBlur: false,
                    search: false,
                    events: { 
                        keyup: function (editor, event) {
                            var text = this.getText();
                            if(text.length > 30){
                                this.setText(text.substring(0, 35));
                            }
                        }
                    }
                });
            } else {
                $('.input_emoji_'+ key).remove()
            }
        }
        $('.timepicker').datetimepicker({
            locale:'vi',
            format: 'HH:mm'
        });
        function addSession() { 
            var numItems = $('.session').length;
            var html = `<div class="form-group row" id="session_`+ (numItems + 1) +`">
                            <div class="col-md-1 pr-0 control-label session">
                                <label for="content">{{ trans('latraining.session') }} `+ (numItems + 1) +`:</label>
                            </div>
                            <div class="col-11">
                                <div class="row wrraper_item">
                                    <div class="col-md-7">
                                        <div class="row m-0">
                                            <div class="col-8 pl-0 pr-1 input_text input_text_`+ (numItems + 1) +`">
                                                <input type="text" name="value_`+(numItems + 1)+`_vi" class="input_emoji_`+(numItems + 1)+`" value="Chào bạn" required>
                                            </div>
                                            <div class="col-4 pr-0 pl-1 d_flex_align">
                                                <input name="time_1[]" type="text" class="form-control timepicker time_1" onblur="checkTime(`+ (numItems + 1) +`)" placeholder="{{ trans('laother.choose_start_time') }}" required value="'23:00:00'">
                                                <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                                <input name="time_2[]" type="text" class="form-control timepicker time_2 mr-1" onblur="checkTime(`+ (numItems + 1) +`)" placeholder="{{ trans('laother.choose_end_time') }}" required value="'23:00:00'">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 p-0">
                                        <input type="color" name="color_text[]" class="avatar avatar-40 shadow-sm change-hover-color"> {{ trans('lasetting.choose_color') }}
                                        <input type="checkbox" name="i_text_`+(numItems + 1)+`" id="i_text_`+(numItems + 1)+`" class="ml-1" onclick="iChecked(`+(numItems + 1)+`)" value="0"> <label for="i_text_`+(numItems + 1)+`">{{ trans('latraining.italic') }}</label>
                                        <input type="checkbox" name="b_text_`+(numItems + 1)+`" id="b_text_`+(numItems + 1)+`" class="ml-1 b_checked" onclick="bChecked(`+(numItems + 1)+`)" value="0"> <label for="b_text_`+(numItems + 1)+`">{{ trans('latraining.bold') }}</label>
                                    </div>
                                    <div class="col-md-1 delete_session"><i class="fa fa-trash text-danger" onclick="removeSession(`+(numItems + 1)+`)"></i></div>
                                </div>
                            </div>
                        </div>`;
            $('.all_session').append(html);
            $('.timepicker').datetimepicker({
                locale:'vi',
                format: 'HH:mm'
            });
            $(".input_emoji_"+(numItems + 1)).emojioneArea({
                pickerPosition: "bottom",
                hidePickerOnBlur: false,
                search: false,
                events: { 
                    keyup: function (editor, event) {
                        var text = this.getText();
                        if(text.length > 30){
                            this.setText(text.substring(0, 35));
                        }
                    }
                }
            });
            var keys = $("input[name=key_language]:checked").map(function(){return $(this).val();}).get();
            if (keys.length > 0) {
                keys.forEach(key => {
                    let input = `<input type="text" name="value_`+(numItems + 1)+`_`+ key +`" class="mt-1 input_emoji input_emoji_`+ key +`_`+ (numItems + 1) +` input_emoji_`+ key +`" value="{Name}" required>`
                    $('.input_text_' + (numItems + 1)).append(input)
                    $(".input_emoji_"+key+'_'+(numItems + 1)).emojioneArea({
                        pickerPosition: "bottom",
                        hidePickerOnBlur: false,
                        search: false,
                        events: { 
                            keyup: function (editor, event) {
                                var text = this.getText();
                                if(text.length > 30){
                                    this.setText(text.substring(0, 35));
                                }
                            }
                        }
                    });
                });
            }
        }

        function removeSession(item) {
            $('#session_'+item).remove();
        }

        function checkTime(id) {
            var error = 0;
            var value_time_1 = $('#session_'+id).find('.time_1').val();
            var value_time_2 = $('#session_'+id).find('.time_2').val();
            console.log(value_time_1, value_time_2);
            if (!value_time_1 || !value_time_2) {
                show_message('{{ trans("laother.please_choose_time") }}', 'error');
                error = 1;
            } else {
                if (value_time_1 >= value_time_2) {
                    show_message('{{ trans("laother.invalid_time") }}', 'error');
                    error = 1;
                } else {
                    var numItems = $('.session').length;
                    for (let index = 0; index < numItems; index++) {
                        var item = index + 1;
                        var value_index_time_1 = $('#session_'+item).find('.time_1').val();
                        var value_index_time_2 = $('#session_'+item).find('.time_2').val();
                        if (item != id && id < item) {
                            if (value_time_1 >= value_index_time_1 || value_time_2 >= value_index_time_1) {
                                show_message('{{ trans("laother.invalid_time") }} ', 'error');
                                error = 1;
                            }
                        } else if (item != id && id > item) {
                            if (value_time_1 <= value_index_time_2 || value_time_2 <= value_index_time_2) {
                                show_message('{{ trans("laother.invalid_time") }} ', 'error');
                                error = 1;
                            }
                        } 
                    } 
                }
            }
            if (error == 1) {
                $('.btn_save_time').attr('disabled',true);
            } else {
                $('.btn_save_time').attr('disabled',false);
            }
        }
        
        function iChecked(id) {
            if($('#i_text_'+id).is(':checked')){
                $('#i_text_'+id).val(1);
            } else {
                $('#i_text_'+id).val(0);
            }
        }
        function bChecked(id) {
            if($('#b_text_'+id).is(':checked')){
                $('#b_text_'+id).val(1);
            } else {
                $('#b_text_'+id).val(0);
            }
        }
        $(document).ready(function() {
            $(".input_emoji").emojioneArea({
                pickerPosition: "bottom",
                hidePickerOnBlur: false,
                search: false,
                events: { 
                    keyup: function (editor, event) {
                        var text = this.getText();
                        if(text.length > 30){
                            this.setText(text.substring(0, 35));
                        }
                    }
                }
            });
        });
    </script>
@stop
