@extends('layouts.backend')

@section('page_title', __(trans('lamenu.permission_approved')))
@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.permission_approved'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection
@section('content')
    <div role="main" id="role">
        @if(isset($errors))

            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach

        @endif
        <div class="row">
            <div class="col-md-8">
                <form role="form" enctype="multipart/form-data" id="form-search">
                    <div class="row">
                        <div class="col-5 pr-0">
                            @include('backend.form_choose_unit')
                        </div>
                        <div class="col-3">
                            <button id="btnsearch" class="btn">
                                <i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}
                            </button>
                        </div>
                    </div>
                </form>
                {{-- @include('permissionapproved::filter') --}}
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('approved-process-create')
                    <div class="btn-group">
                        <button class="btn" data-toggle="modal" data-target="#modal-approved-process"><i class="fa fa-plus-circle"></i> @lang('labutton.add_new')</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <br>
        <form id="form-approved-process" >
            <table class="tDefault table table-hover bootstrap-table">
                <thead>
                <tr>
                    <th data-formatter="index_formatter" data-align="center" data-width="50" >{{ trans('latraining.stt') }}</th>
                    <th data-field="unit_name" data-formatter="name_formatter" data-width="200" >{{ trans('latraining.unit') }}</th>
                    <th  data-field="branch_name"  data-width="500" >{{ trans('latraining.branch_effective') }}</th>
                    <th  data-field="unit_id" data-formatter="setup_formatter" data-align="center"  data-width="40" >{{ trans('latraining.setting') }}</th>
                    @can('approved-process-delete')
                    <th  data-field="id" data-formatter="delete_formatter"  data-width="70" >{{ trans('labutton.delete') }}</th>
                    @endcan
                </tr>
                </thead>
            </table>
        </form>
    </div>
    @include('permissionapproved::modal.create_approved_process')
        <script type="text/javascript">
            function index_formatter(value, row, index) {
                return index +1;
            }
            function setup_formatter(value, row, index) {
                return '<a href="'+base_url+'/admin-cp/permission-approved?unit_id='+row.unit_id+'" class="text-primary"><i class="fas fa-cog fa-2x"></i></a>';
            }
            function name_formatter(value, row, index) {
                return '<a href="'+base_url+'/admin-cp/permission-approved?unit_id='+row.unit_id+'" class="text-primary">'+ row.name +'</a>';
            }
            function delete_formatter(value,row, index) {
                return `<a href="javascript:void(0)" data-id="${value}" class="btn remove-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</a>`;
            }
            var table = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: '{{ route('backend.approved.process.index', []) }}',
                remove_url: '{{route('backend.approved.process.delete')}}',
            });
            $('#save-approved-process').on('click',function () {
                var $this = $(this);
                var form = $(this).closest('form');
                var url = form.attr('action');
                var formData = new FormData(form[0]);
                let btn = $(this);
                let current_icon = btn.find('i').attr('class');
                btn.find('i').attr('class', 'fa fa-spinner fa-spin');
                btn.prop("disabled", true);
                $.ajax({
                    url: url,
                    type: 'post',
                    data: formData,
                    dataType: 'json',
                    cache:false,
                    contentType: false,
                    processData: false
                }).done(function (result) {
                    show_message(result.message,result.status);
                    if (result.status=='success'){
                        table.refresh();
                        $this.closest('.modal').modal('hide');
                    }
                    btn.find('i').attr('class', current_icon);
                    btn.prop("disabled", false);
                })
                .fail(function(result) {
                    btn.find('i').attr('class', current_icon);
                    btn.prop("disabled", false);
                    return false;
                });
            });
        </script>
@endsection
