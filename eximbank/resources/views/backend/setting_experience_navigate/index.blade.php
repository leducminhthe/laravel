@extends('layouts.backend')

@section('page_title', trans('lamenu.experience_directed'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.experience_directed'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item">
                    <a href="#time" class="nav-link active" role="tab" data-toggle="tab">{{ trans('latraining.time') }}</a>
                </li>
                <li class="nav-item">
                    <a href="#name" class="nav-link " data-toggle="tab">{{ trans('latraining.navigating_name') }}</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="time" class="tab-pane active">
                    <div role="main">
                        <div class="row">
                            <div class="col-md-8">
                            </div>
                            <div class="col-md-4 text-right act-btns">
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <a href="{{ route('backend.experience_navigate.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                                        <button class="btn" id="delete-navigate"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <table id="table-time" class="tDefault table table-hover bootstrap-table text-nowrap">
                            <thead>
                                <tr>
                                    <th data-field="state" data-checkbox="true"></th>
                                    <th data-field="image" data-align="center" data-formatter="date_formatter" data-width="20%">{{ trans('latraining.date') }}</th>
                                    <th data-field="image" data-align="center" data-formatter="time_formatter" data-width="20%">{{ trans('latraining.time') }}</th>
                                    <th data-field="total_count"  data-align="center" data-width="20%">{{ trans('latraining.maximum_impressions') }}</th>
                                    <th data-field="date_count"  data-align="center" data-width="20%">{{ trans('laother.maximum_impressions_day') }}</th>
                                    <th data-field="edit"  data-align="center" data-formatter="edit_formatter" data-width="20%">{{ trans("labutton.edit") }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div id="name" class="tab-pane ">
                    <div role="main">
                        {{-- <div class="row">
                            <div class="col-md-8">
                            </div>
                            <div class="col-md-4 text-right act-btns">
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <a href="{{ route('backend.experience_navigate.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <br>
                        <table id="table-name" class="tDefault table table-hover bootstrap-table text-nowrap">
                            <thead>
                                <tr>
                                    <th data-field="state" data-checkbox="true"></th>
                                    <th data-field="name" data-align="center" data-formatter="name_edit_formatter">{{ trans('laother.name_img_navigate') }}</th>
                                    <th data-field="status" data-align="center" data-formatter="status_edit_formatter" data-width="5%">{{ trans('lacategory.status') }}</th>
                                    {{-- <th data-field="url">Đường dẫn</th> --}}
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal right fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save" onsubmit="return false;">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @can('setting-experience-navigate-name-edit')
                                <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="image">{{ trans('laother.display_time') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-7">
                                <select name="type" class="form-control select2" id="type" data-placeholder="{{ trans('laother.type_form') }}">
                                    <option value=""></option>
                                    <option value="1">{{ trans('laforums.word') }}</option>
                                    <option value="2">{{ trans('lacategory.image') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row wrapped_text">
                            <div class="col-sm-4 control-label">
                                <label for="image">{{ trans('latraining.navigating_name') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="name_navigate[]" class="form-control name_navigate_vi" maxlength="40" value="">
                            </div>
                            <div class="col-1">
                                Vi
                            </div>
                        </div>
                        @foreach ($languagesType as $item)
                            <div class="form-group row wrapped_text">
                                <div class="col-sm-4 control-label">
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" name="name_navigate[]" class="form-control name_navigate_{{ $item->key }}" maxlength="40" value="">
                                </div>
                                <div class="col-1">
                                    {{ $item->key }}
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group row wrapped_img">
                            <div class="col-sm-4 control-label">
                                <label>{{trans('lahandle_situations.image')}} <span class="text-danger">*</span> <br> (150 x 150)</label>
                            </div>
                            <div class="col-md-7">
                                <a href="javascript:void(0)" id="select-image">{{trans('lahandle_situations.choose_picture')}}</a>
                                <div id="image-review">
                                </div>
                                <input name="image" id="image-select" type="text" class="d-none" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label>{{ trans('lacategory.status') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <label class="radio-inline"><input id="enable" class="status" type="radio" required name="status" value="1" checked> {{ trans('lacategory.enable') }}</label>
                                <label class="radio-inline"><input id="disable" class="status" type="radio" required name="status" value="0"> {{ trans('lacategory.disable') }}</label>
                            </div>
                        </div>
                        <div class="form-group row url_name">
                            <div class="col-sm-4 control-label mt-2">
                                <label for="image">{{ trans('lasetting.url') }}</label>
                            </div>
                            <div class="col-sm-7 wrraped_name">
                                <span class="name_url"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function time_formatter(value, row, index) {
            var html = '';
            $.each(row.time, function (index, value) {
                html += value;
                html += '</br>'
            });
            return html;
        }

        function date_formatter(value, row, index) {
            var html = '<span>'+ row.start_date +'</span></br><span>'+ row.end_date +'</span>';
            return html;
        }

        function edit_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'"><i class="far fa-edit"></i></a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.experience_navigate.getdata') }}',
            remove_url: '{{ route('backend.experience_navigate.remove') }}',
            table: '#table-time',
            detete_button: "#delete-navigate"
        });

        function name_edit_formatter(value, row, index) {
            if (row.type == 1) {
                return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
            } else {
                return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')"><img src="'+ row.image + '" width="100px" height="auto"></a>' ;
            }
        }

        function status_edit_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        var table_name = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.experience_navigate.getdata_name') }}',
            remove_url: '',
            table: '#table-name',
            detete_button: ""
        });

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('{{ trans("lacore.min_one_course") }}', 'error');
                    return false;
                }
            }
            $.ajax({
                url: "{{ route('backend.experience_navigate.ajax_isopen_publish') }}",
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                if (id == 0) {
                    show_message(data.message, data.status);
                }
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        };

        function edit(id){
            switch (id) {
                case 1:
                    var url = '{{ route("module.frontend.user.my_career_roadmap") }}';
                    $('.name_url').html(url);
                    break;
                case 2:
                    var url = "{{ route('frontend.all_course',['type' => 3]) }}";
                    $('.name_url').html(url);
                    break;
                case 3:
                    var url = "{{ route('frontend.all_course',['type' => 3]) }}";
                    $('.name_url').html(url);
                    break;
                case 4:
                    var url = "{{ route('forums_react') }}";
                    $('.name_url').html(url);
                    break;
                case 5:
                    var url = "{{ route('frontend.all_course',['type' => 1]) }}";
                    $('.name_url').html(url);
                    break;
                case 6:
                    var url = "{{ route('news_react') }}";
                    $('.name_url').html(url);
                    break;
                case 7:
                    var url = "{{ route('module.frontend.user.info') }}";
                    $('.name_url').html(url);
                    break;
                case 8:
                    var url = "{{ route('library',['type' => 2]) }}";
                    $('.name_url').html(url);
                    break;
                case 9:
                    var url = "{{ route('daily_training_react',['type' => 0]) }}";
                    $('.name_url').html(url);
                    break;
            }
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('backend.experience_navigate.edit_name') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans("labutton.edit") }}');
                $("input[name=id]").val(data.model.id);
                $("input[name=name_navigate]").val(data.name);

                data.languagesType.forEach(element => {
                    $('.name_navigate_'+ element).val(data.name[element])
                });

                $("#type").val(data.model.type);
                $("#type").val(data.model.type).change();

                if (data.model.type == 1) {
                    $('.wrapped_text').show();
                    $('.wrapped_img').hide();
                    $("input[name=image]").val('');
                    $("#image-review").html('<img src="" alt="">');
                } else {
                    $('.wrapped_text').hide();
                    $('.wrapped_img').show();
                    $("input[name=image]").val(data.model.image);
                    $("#image-review").html('<img class="w-100" src="'+ data.model.image +'" alt="">');
                }

                if (data.model.status == 1) {
                    $('#enable').prop( 'checked', true )
                    $('#disable').prop( 'checked', false )
                } else {
                    $('#enable').prop( 'checked', false )
                    $('#disable').prop( 'checked', true )
                }
                $('#modal-popup').modal();
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function save(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save').attr('disabled',true);

            var form = $('#form_save');
            var name = $("input[name='name_navigate[]']").map(function(){return $(this).val();}).get();
            var id =  $("input[name=id]").val();
            var status = $('.status:checked').val();
            var image = $("input[name=image]").val();
            var type = $("#type").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.experience_navigate.save_name') }}",
                type: 'post',
                data: {
                    'name': name,
                    'id': id,
                    'status': status,
                    'image' : image,
                    'type': type
                }
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#modal-popup').modal('hide');
                    show_message(data.message, data.status);
                    $(table_name.table).bootstrapTable('refresh');
                } else {
                    show_message(data.message, data.status);
                }
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        $('#type').on('change', function() {
            var type = $('#type').val();
            if (type == 1) {
                $('.wrapped_text').show();
                $('.wrapped_img').hide();
            } else {
                $('.wrapped_text').hide();
                $('.wrapped_img').show();
            }
        })

        $("#select-image").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review").html('<img class="w-100" src="'+ path +'">');
                $("#image-select").val(path);
            });
        });
    </script>
@endsection
