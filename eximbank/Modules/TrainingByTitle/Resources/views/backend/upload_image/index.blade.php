@extends('layouts.backend')

@section('page_title', 'Hình ảnh Lộ trình đào tạo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title">
            <i class="uil uil-apps"></i>
            {{ trans('lamenu.training') }}
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Hình ảnh Lộ trình đào tạo</span>
        </h2>
    </div>
@endsection
@section('content')
    <div role="main">
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="2%">#</th>
                    <th data-field="image" data-width="60%" data-align="center" data-formatter="image_formatter">Ảnh</th>
                    <th data-field="type" data-align="center" data-formatter="type_formatter">Đối tượng</th>
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
                            @canany('training-by-title-detail-create')
                                <button type="button" onclick="save(event)" class="btn save">{{ trans('labutton.save') }}</button>
                            @endcan
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label>{{trans('lahandle_situations.image')}} ({{trans('lahandle_situations.size')}}: 300 x 500)</label>
                            </div>
                            <div class="col-md-7">
                                <a href="javascript:void(0)" id="select-image">{{trans('lahandle_situations.choose_picture')}}</a>
                                <div id="image-review">
                                </div>
                                <input name="image" id="image-select" type="text" class="d-none" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<img src="" alt="">

    <script type="text/javascript">
        function image_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')"><img src="'+ row.image2 +'" alt="" width="50%"></a>' ;
        }

        function index_formatter(value, row, index) {
            return (index+1);
        }

        function type_formatter(value, row, index) {
            if(row.type == 1) {
                return '<span>Nam</span>'
            } else {
                return '<span>Nữ</span>'
            }
        }
        
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_by_title.getdata_upload_image') }}',
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
            $.ajax({
                url: "{{ route('module.training_by_title.edit_upload_image') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('{{ trans("labutton.edit") }}');
                $("input[name=id]").val(data.model.id);
                $("input[name=image]").val(data.model.image);
                $("#image-review").html('<img class="w-100" src="'+ data.image +'" alt="">');
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

            var id =  $("input[name=id]").val();
            var image = $("input[name=image]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.training_by_title.save_upload_image') }}",
                type: 'post',
                data: {
                    'id': id,
                    'image' : image
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
