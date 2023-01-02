@extends('layouts.backend')

@section('page_title', trans('lamenu.forum'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.forum'),
                'url' => route('module.forum.category')
            ],
            [
                'name' => trans('laforums.filter_word'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main">
    <div class="row">
        <div class="col-md-6">
            <form class="form-inline form-search mb-3" id="form-search">
                <input type="text" name="search" value="" class="form-control" placeholder="{{trans('laforums.enter_name_category')}}">
                <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
            </form>
        </div>
        <div class="col-md-6 text-right act-btns">
            <div class="pull-right">
                @can('forum-status')
                    <div class="btn-group">
                        <button class="btn" onclick="changeStatus(0,1)" data-status="1">
                            <i class="fa fa-check-circle"></i> &nbsp;{{ trans('labutton.enable') }}
                        </button>
                        <button class="btn" onclick="changeStatus(0,0)" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('labutton.disable') }}
                        </button>
                    </div>
                @endcan
                <div class="btn-group">
                    @can('forum-create')
                        <button style="cursor: pointer;" onclick="create()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                    @endcan
                    @can('forum-delete')
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
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
                <th data-field="name" data-formatter="name_formatter">{{ trans('laforums.word') }}</th>
                <th data-field="status" data-width="5%" data-formatter="status_formatter" data-align="center" >{{ trans('laforums.status') }}</th>
            </tr>
        </thead>
    </table>
</div>

<div class="modal fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" id="ajax-modal-popup" role="document">
        <form action="" method="post" class="form-ajax" id="form_save" onsubmit="return false;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="body_modal">
                    <input type="hidden" name="id" value="">
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="name">{{ trans('laforums.word') }}<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <input name="name" type="text" class="form-control" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{trans('laforums.status')}} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <label class="radio-inline">
                                <input id="enable" class="status" type="radio" required name="status" value="1">{{ trans('laforums.enable') }}
                            </label>
                            <label class="radio-inline">
                                <input id="disable" class="status" type="radio" required name="status" value="0">{{ trans('laforums.disable') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @can('forum-create')
                        <button type="button" onclick="save(event)" class="btn">{{ trans('labutton.save') }}</button>
                    @endcan
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function name_formatter(value, row, index) {
        return '<a style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
    }

    function status_formatter(value, row, index) {
        var status = row.status == 1 ? 'checked' : '';
        var html = `<div class="custom-control custom-switch">
                        <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                        <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                    </div>`;
        return html;
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.forum.category.filter.getword') }}',
        remove_url: '{{ route('module.forum.category.filter_save.remove') }}'
    });

    function changeStatus(id,status) {
        if (id && !status) {
            var ids = id;
            var checked = $('#customSwitch_' + id).is(":checked");
            var status = checked == true ? 1 : 0;
        } else {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('{{ trans('lacourse.min_one_course ') }}', 'error');
                return false;
            }
        }
        $.ajax({
            url: "{{ route('module.forum.category.filter_word.ajax_isopen_publish') }}",
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
        $.ajax({
            url: "{{ route('module.forum.category.filter_word.edit') }}",
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            $('#exampleModalLabel').html('{{ trans('laforums.edit') }} ' + data.name);
            $("input[name=id]").val(data.id);
            $("input[name=name]").val(data.name);
            if (data.status == 1) {
                $('#enable').prop( 'checked', true )
                $('#disable').prop( 'checked', false )
            } else {
                $('#enable').prop( 'checked', false )
                $('#disable').prop( 'checked', true )
            }
            $('#modal-popup').modal();
            return false;
        }).fail(function(data) {
            show_message('{{ trans('laother.data_error') }}', 'error');
            return false;
        });
    }

    function save(event) {
        var form = $('#form_save');
        var name =  $("input[name=name]").val();
        var id =  $("input[name=id]").val();
        var status = $('.status:checked').val();
        event.preventDefault();
        $.ajax({
            url: "{{ route('module.forum.category.filter_save') }}",
            type: 'post',
            data: {
                'name': name,
                'id': id,
                'status': status,
            }
        }).done(function(data) {
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
        $('#exampleModalLabel').html('{{ trans('laforums.add_new') }}');
        $('#modal-popup').modal();
    }
</script>
@endsection
