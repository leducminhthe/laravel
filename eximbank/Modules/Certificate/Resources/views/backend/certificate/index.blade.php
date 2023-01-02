@extends('layouts.backend')

@section('page_title', trans('lamenu.certificate'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('laprofile.certificates'),
                'url' => route('module.certificate')
            ],
            [
                'name' => trans('lamenu.certificate'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')

    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.search_code_name')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('certificate-template-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                        @endcan
                        @can('certificate-template-delete')
                            <button class="btn" id="delete-item" ><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="code" data-width="10%" data-align="center">{{trans('backend.certificate_code')}}</th>
                    <th data-field="name" data-formatter="name_formatter">{{trans('backend.certificate_name')}}</th>
                    <th data-field="image" data-formatter="image_formatter" data-align="center">{{trans('backend.certificate')}}</th>
                    <th data-field="type" data-width="15%">{{trans('backend.type')}}</th>
                    <th data-field="regist" data-align="center" data-formatter="created_formatter" data-width="5%">{{ trans('laother.creator') }}</th>
                    <th data-field="regist" data-align="center" data-formatter="updated_formatter" data-width="5%">{{ trans('laother.editor') }}</th>
                    <th data-field="design" data-align="center" data-width="5%">{{ trans('latraining.design') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal right fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save" onsubmit="return false;">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="btn-group">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                        </div>
                        <div class="btn-group act-btns">
                            @canany(['certificate-template-create', 'certificate-template-edit'])
                                <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
						<div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('latraining.type') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <label class="radio-inline">
                                    <input type="radio" required name="type" value="1">{{ trans('latraining.course') }}
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" required name="type" value="2">{{ trans('latraining.training_program') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="code">{{trans('backend.certificate_code')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="code" type="text" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="name">{{trans('backend.certificate_name')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="name" type="text" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{trans('backend.picture')}} (1200x848) <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-5">
                                <a href="javascript:void(0)" id="select-image">{{trans('latraining.choose_picture')}}</a>
                                <div id="image-review">
                                </div>
                                <input name="image" id="image-select" type="text" class="d-none" value="">
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="user">Họ tên người đại diện<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="user" type="text" class="form-control" value="">
                            </div>
                        </div> --}}
                        {{-- <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="position">Chức vụ <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <input name="position" type="text" class="form-control" value="">
                            </div>
                        </div> --}}

                        {{-- CHỮ KÝ CHO CHỨNG CHỈ --}}
                        {{-- <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>Chữ ký <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-5">
                                <a href="javascript:void(0)" id="select-image-signature">{{trans('latraining.choose_picture')}}</a>
                                <div id="image-signature-review">
                                </div>
                                <input name="signature" id="image-signature-select" type="text" class="d-none" value="">
                            </div>
                        </div> --}}

                        {{-- LOGO CHO CHỨNG CHỈ --}}
                        {{-- <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>Logo (300x80) <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-5">
                                <a href="javascript:void(0)" id="select-image-logo">{{trans('latraining.choose_picture')}}</a>
                                <div id="image-logo-review">
                                </div>
                                <input name="logo" id="image-logo-select" type="text" class="d-none" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="location">Vị trí logo <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <select name="location" id="location" class="form-control select2">
                                    <option value="left">Trái</option>
                                    <option value="center">Giữa</option>
                                    <option value="right">Phải</option>
                                </select>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function created_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_created+'"><i class="fa fa-user"></i></a>';
        }

        function updated_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.user_updated+'"><i class="fa fa-user"></i></a>';
        }

        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        function image_formatter(value, row, index) {
            return '<img src="'+ row.image +'" class="img-responsive" width="100" height="100">';
        }

        var table = new LoadBootstrapTable({
            url: '{{ route('module.certificate.getdata') }}',
            remove_url: '{{ route('module.certificate.remove') }}',
            locale: '{{ \App::getLocale() }}',
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('module.certificate.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $("input[name=type]").prop('disabled', false);

                $('#exampleModalLabel').html('{{ trans('labutton.edit') }}');
                $("input[name=id]").val(data.model.id);
                $("input[name=code]").val(data.model.code);
                $("input[name=name]").val(data.model.name);
                $("input[name=image]").val(data.model.image);
                $("input[name=user]").val(data.model.user);
                $("input[name=position]").val(data.model.position);
                $("input[name=signature]").val(data.model.signature);
                $("input[name=logo]").val(data.model.logo);
                $("input[name=type][value=" + data.model.type + "]").prop('checked', true);

                $("#location").val(data.model.location).change();

                $("#image-review").html('<img class="w-100" src="'+ data.image +'" alt="">');
                $("#image-signature-review").html('<img class="w-100" src="'+ data.signature +'" alt="">');
                $("#image-logo-review").html('<img class="w-100" src="'+ data.logo +'" alt="">');

                if(data.check_has_cert == 1){
                    $("input[name=type]:radio:not(:checked)").prop('disabled', true);
                }

                $('#modal-popup').modal();
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function save(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
            $('.save').attr('disabled',true);

            var form = $('#form_save');
            var name =  $("input[name=name]").val();
            var id =  $("input[name=id]").val();
            var code =  $("input[name=code]").val();
            var image = $("input[name=image]").val();
            var user = $("input[name=user]").val();
            var position = $("input[name=position]").val();
            var signature = $("input[name=signature]").val();
            var logo = $("input[name=logo]").val();
            var type = $("input[name=type]:checked").val();
            var location = $("#location").val();

            event.preventDefault();
            $.ajax({
                url: "{{ route('module.certificate.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'code': code,
                    'id': id,
                    'image': image,
                    'user' : user,
                    'position' : position,
                    'signature' : signature,
                    'logo' : logo,
                    'type' : type,
                    'location' : location,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#modal-popup').modal('hide');
                    show_message(data.message, data.status);
                    $(table.table).bootstrapTable('refresh');
                } else {
                    show_message(data.message, data.status);
                }
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        function create() {
            $("input[name=name]").val('');
            $("input[name=id]").val('');
            $("input[name=code]").val('');
            $("input[name=image]").val('');
            $("input[name=user]").val('');
            $("input[name=position]").val('');
            $("input[name=signature]").val('');
            $("input[name=logo]").val('');

            $('#exampleModalLabel').html('{{ trans('labutton.add_new') }}');
            $('#modal-popup').modal();

            $("#image-review").html('<img src="" alt="">');
            $("#image-signature-review").html('<img src="" alt="">');
            $("#image-logo-review").html('<img src="" alt="">');
        }

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

        $("#select-image-signature").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-signature-review").html('<img class="w-100" src="'+ path +'">');
                $("#image-signature-select").val(path);
            });
        });

        $("#select-image-logo").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=1100,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-logo-review").html('<img class="w-100" src="'+ path +'">');
                $("#image-logo-select").val(path);
            });
        });
    </script>
@endsection
