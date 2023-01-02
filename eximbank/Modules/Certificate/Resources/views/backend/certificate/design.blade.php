@extends('layouts.backend')

@section('page_title', trans('latraining.design'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('laprofile.certificates'),
                'url' => ''
            ],
            [
                'name' => trans('backend.certificate'),
                'url' => route('module.certificate')
            ],
            [
                'name' => trans('latraining.design'),
                'url' => ''
            ]
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('header')
    <script src="{{ asset('js/jquery-ui-1.13.1.custom/jquery-ui.min.js') }}" type="text/javascript"></script>
    <style>
        .item_design { 
            position: absolute;
            display: flex;
            align-items: center
        }
        .item_design .pndd { 
            cursor: pointer; 
            color: red;
        }
        .item_design .pndd span {  
            font-size: 60px; 
            color: red;
        }
        .font_size {
            font-size: 13px;
            line-height: 13px;
            width: 25px;
            text-align: center;
            top: 0px;
            display: none;
            margin-left: 5px;
            z-index: 10;
        }
        .status_item {
            display: none;
            z-index: 10;
            background: white;
            font-size: 13px;
            line-height: 13px;
            padding: 2px 5px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            margin-left: 2px
        }
        .hide {
            opacity: 0.5;
        }
        .image_certificate {
            position: relative;
        }
        .pnlayout {
            overflow: auto
        }
        @font-face {
            font-family: myFirstFont;
            src: url("../../fonts/UTM Wedding K&T.ttf");
        }
        #item_fullname .title_item {
            font-family: myFirstFont;
        }
        .color_item {
            display: none;
            width: 20px;
            height: 20px;
            cursor: pointer;
            margin-left: 2px;
        }
        .new_info {
            font-size: 18px;
            line-height: 18px;
            color: red;
            top: 0px;
            left: 0px;
        }
    </style>
@endsection

@section('content')
<div role="main">
    <div class="row">
        <div class="col-md-6">
        </div>
        <div class="col-md-6 text-right">
            <div class="btn-group act-btns">
                @canany(['certificate-template-create', 'certificate-template-edit'])
                    <button id="btnsave" type="button" class="btn"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                @endcanany
                <button id="btnpreview" type="button" class="btn"><i class="fa fa-eye"></i> &nbsp;{{ trans('labutton.preview_new') }}</button>
                <a href="{{ route('module.certificate') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="pnlayout">

        <form id="formpreview" method="post" action="{{route('module.certificate.preview',["id"=>$model->id])}}" target="_blank">
            @csrf
            <div class="pnitem mt-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row all_info">
                            <div class="col-md-3">
                                <label for="">Họ và tên</label> |
                                <span id="dropdown_item_fullname" class="dropdown cursor_pointer">
                                    <span class="dropdown-toggle pndd" data-toggle="dropdown">
                                        {{isset($items["fullname"][2])?$aligndesign[$items["fullname"][2]]:'Canh trái'}} <span class=" caret"></span>
                                    </span>
                                    <ul class="dropdown-menu">
                                        <li><a href="#" data-item="item_fullname" data-align="left">Canh trái</a></li>
                                        <li><a href="#" data-item="item_fullname" data-align="center">Canh giữa</a></li>
                                        <li><a href="#" data-item="item_fullname" data-align="right">Canh phải</a></li>
                                    </ul>
                                </span>
                                <input type="text" class="form-control" name="text_fullname" id="text_fullname" onblur="changeText('fullname')">
                                <input type="hidden" value="{{isset($items["fullname"][2])?$items["fullname"][2]:'left'}}" id="align_item_fullname"/>
                            </div>
                            @if ($model->type == 2)
                                <div class="col-md-3">
                                    <label for="">CT đào tạo</label> |
                                    <span id="dropdown_item_subject_type" class="dropdown cursor_pointer">
                                        <span class="dropdown-toggle pndd" data-toggle="dropdown">
                                            {{isset($items["subject_type"][2])?$aligndesign[$items["subject_type"][2]]:'Canh trái'}} <span class=" caret"></span>
                                        </span>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" data-item="item_subject_type" data-align="left">Canh trái</a></li>
                                            <li><a href="#" data-item="item_subject_type" data-align="center">Canh giữa</a></li>
                                            <li><a href="#" data-item="item_subject_type" data-align="right">Canh phải</a></li>
                                        </ul>
                                    </span>
                                    <input type="text" class="form-control" name="text_subject_type" id="text_subject_type" onblur="changeText('subject_type')">
                                    <input type="hidden" value="{{isset($items["subject_type"][2])?$items["subject_type"][2]:'left'}}" id="align_item_subject_type"/>
                                </div>
                            @else 
                                <div class="col-md-3">
                                    <label for=""> Khóa học</label> |
                                    <span id="dropdown_item_course_name" class="dropdown cursor_pointer">
                                        <span class="dropdown-toggle pndd" data-toggle="dropdown">
                                            {{isset($items["course_name"][2])?$aligndesign[$items["course_name"][2]]:'Canh trái'}} <span class=" caret"></span>
                                        </span>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" data-item="item_course_name" data-align="left">Canh trái</a></li>
                                            <li><a href="#" data-item="item_course_name" data-align="center">Canh giữa</a></li>
                                            <li><a href="#" data-item="item_course_name" data-align="right">Canh phải</a></li>
                                        </ul>
                                    </span>
                                    <input type="text" class="form-control" name="text_course_name" id="text_course_name" onblur="changeText('course_name')">
                                    <input type="hidden" value="{{isset($items["course_name"][2])?$items["course_name"][2]:'left'}}" id="align_item_course_name"/>
                                </div>

                                <div class="col-md-3">
                                    <label for=""> Mã </label> |
                                    <span id="dropdown_item_course_code" class="dropdown cursor_pointer">
                                        <span class="dropdown-toggle pndd" data-toggle="dropdown">
                                            {{isset($items["course_code"][2])?$aligndesign[$items["course_code"][2]]:'Canh trái'}} <span class=" caret"></span>
                                        </span>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" data-item="item_course_code" data-align="left">Canh trái</a></li>
                                            <li><a href="#" data-item="item_course_code" data-align="center">Canh giữa</a></li>
                                            <li><a href="#" data-item="item_course_code" data-align="right">Canh phải</a></li>
                                        </ul>
                                    </span>
                                    <input type="text" class="form-control" name="text_course_code" id="text_course_code" onblur="changeText('course_code')">
                                    <input type="hidden" value="{{isset($items["course_code"][2])?$items["course_code"][2]:'left'}}" id="align_item_course_code"/>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <label for=""> Chức danh </label> |
                                <span id="dropdown_item_title" class="dropdown cursor_pointer">
                                    <span class="dropdown-toggle pndd" data-toggle="dropdown">
                                        {{isset($items["title"][2])?$aligndesign[$items["title"][2]]:'Canh trái'}} <span class=" caret"></span>
                                    </span>
                                    <ul class="dropdown-menu">
                                        <li><a href="#" data-item="item_title" data-align="left">Canh trái</a></li>
                                        <li><a href="#" data-item="item_title" data-align="center">Canh giữa</a></li>
                                        <li><a href="#" data-item="item_title" data-align="right">Canh phải</a></li>
                                    </ul>
                                </span>
                                <input type="text" class="form-control" name="text_title" id="text_title" onblur="changeText('title')">
                                <input type="hidden" value="{{isset($items["title"][2])?$items["title"][2]:'left'}}" id="align_item_title"/>
                            </div>
                            <div class="col-md-3">
                                <label for="">TP/Ngày/tháng/năm</label>
                                <input type="text" class="form-control" name="text_date" id="text_date" onblur="changeText('date')">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="form_certificate_design" action="" method="post" class="form-ajax" enctype="multipart/form-data">
            <div id="container" style=" background:url({{$image}}) no-repeat; background-size: 1280px 848px; width:1280px; height:848px;margin:20px auto; position:relative;">
                @foreach ($items as $key => $item)
                    <input type="hidden" 
                        name="input_item_name[]"
                        value="{{ $key }}"
                    />

                    <input type="hidden" 
                        name="input_item_location[]"
                        id="location_item_{{ $key }}"
                        value="{{ $item[2] }}"
                    />

                    <input 
                        type="hidden" value="{{ $item[0] }}-{{ $item[1] }}" 
                        name="input_item_pos[]"
                        id="pos_item_{{ $key }}"
                    />

                    <input 
                        type="hidden" value="{{ $item[4] }}" 
                        name="input_item_status[]"
                        id="input_status_item_{{ $key }}"
                    />

                    <input 
                        type="hidden" value="{{ $item[5] }}" 
                        name="input_item_color[]"
                        id="input_color_item_{{ $key }}"
                    />

                    <input 
                        type="hidden" value="{{ $item[3] }}" 
                        name="input_item_font_szie[]"
                        id="input_font_size_item_{{ $key }}"
                    />

                    <input 
                        type="hidden" value="{{ $item[6] }}" 
                        name="input_item_value[]"
                        id="input_value_item_{{ $key }}"
                    />
                    
                    @if (empty($item[6]))
                        <span id="item_{{ $key }}" 
                            style="top:{{ $item[1] }}px; left:{{ $item[0] }}px;" 
                            class="item_design cursor_pointer"
                            onclick="showEditFont('item_{{ $key }}')"
                        >
                            <img class="title_item {{ $item[4] == 0 ? 'hide' : '' }}" src="{{ $item[5] }}" alt="" title="" />
                            <span class="cursor_pointer status_item" id="status_item_{{ $key }}" onclick="statusItem('item_{{ $key }}')">
                                <i class="fas {{ $item[4] == 0 ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                            </span>
                        </span>
                    @else
                        <span id="item_{{ $key }}" 
                            style="line-height:{{ $item[3] }}px; font-size:{{ $item[3] }}px; color:{{ $item[5] }}; top:{{ $item[1] }}px; left:{{ $item[0] }}px;" 
                            class="item_design cursor_pointer"
                            onclick="showEditFont('item_{{ $key }}')"
                        >
                            <span class="title_item  title_item_{{ $key }} {{ $item[4] == 0 ? 'hide' : '' }}">{{ $item[6] }}</span>
                            <input type="number" 
                                value="{{ $item[3] }}" 
                                id="font_size_item_{{ $key }}" 
                                class="font_size" 
                                onblur="changeFontSizeHandle('item_{{ $key }}')"
                            >
                            <span class="cursor_pointer status_item" id="status_item_{{ $key }}" onclick="statusItem('item_{{ $key }}')">
                                <i class="fas {{ $item[4] == 0 ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                            </span>
                            <input type="color" id="color_item_{{ $key }}" class="color_item" onchange="colorItem('item_{{ $key }}')" value="{{ $item[5] }}">
                        </span>
                    @endif
                @endforeach
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $(window).on('load', function() {
            draggable()
        })

        $(document).ready(function(){
         //   moveDropdown();
            $(".dropdown-menu li a").click(function(){
                $(this).parents(".dropdown").find('.pndd').html($(this).text() + ' <span class="caret"></span>');
                $(this).parents(".dropdown").find('.pndd').val($(this).data('value'));
            });

            $(".dropdown-menu > li > a").on('click', function () {
                var align = $(this).attr('data-align');
                var item = $(this).attr('data-item');
                $("#align_"+item).val(align);
                $("#location_"+item).val(align);
            });

            $("#btnsave").on('click', function () {
                event.preventDefault();
                $.ajax({
                    url: "{{ route('module.certificate.design.save',["id_cert"=>$model->id]) }}",
                    type: 'post',
                    dataType: 'json',
                    data: $('#form_certificate_design').serialize(),
                }).done(function(data) {
                    show_message(data.message, data.status);
                    return false;
                }).fail(function(data) {
                    show_message('{{ trans('laother.data_error') }}', 'error');
                    return false;
                });

            });

            $("#btnpreview").on('click', function () {
                $('#formpreview').submit();
            });
        });

        function draggable() {
            $(".item_design").draggable({
                containment: "#container",
                start: function(event, ui) {
                },
                stop: function(event, ui) {
                    var pos = $(this).position();
                    var top = parseInt(pos.top);
                    var id = $(this).attr('id');                    
                    $("#pos_"+id).val(parseInt(pos.left) + "-" + top);
                    console.log(pos.left, top);
                }
            });
        }

        function moveDropdown(){
            var pi = $( "#item_fullname" ).offset();
            if(pi.left==0)  $( "#item_fullname" ).offset({ left: 1300 });
        }

        function changeFontSizeHandle(name) {
            var fontSizeItem = $('#font_size_'+ name).val();
            $('#input_font_size_'+ name).val(fontSizeItem);
            $('#'+ name).css({
                fontSize: fontSizeItem + 'px',
                lineHeight: fontSizeItem + 'px'
            });
        }

        function showEditFont(name) {
            $('.font_size').hide();
            $('.status_item').hide();
            $('.color_item').hide();

            $('#font_size_'+ name).show();
            $('#status_'+ name).show();
            $('#color_'+ name).show();
        }

        function statusItem(name) {
            if($('#status_'+ name).find('i').hasClass("fa-eye")) {
                $('#status_'+ name).find('i').removeClass("fa-eye")
                $('#status_'+ name).find('i').addClass("fa-eye-slash")
                $('#'+ name).find('.title_item').addClass('hide')
                $('#input_status_'+ name).val(0)
            } else {
                $('#status_'+ name).find('i').addClass("fa-eye")
                $('#status_'+ name).find('i').removeClass("fa-eye-slash")
                $('#'+ name).find('.title_item').removeClass('hide')
                $('#input_status_'+ name).val(1)
            }
        }

        function colorItem(name) {
            var colorItem = $('#color_'+ name).val();
            $('#input_color_'+ name).val(colorItem);
            $('#'+ name).css({
                color: colorItem,
            });
        }

        function changeText(name) {
            var text = $('#text_'+ name).val()
            $('.title_item_'+ name).html(text)
        }
    </script>
</div>
@stop
