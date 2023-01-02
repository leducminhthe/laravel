@extends('layouts.backend')

@section('page_title', trans('lamenu.interaction_history_clear'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.interaction_history_clear'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <form id="form_save" onsubmit="return false;" method="post" action="{{ route('backend.interaction_history_clear.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('latraining.time_to_delete') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="date_clear" id="date_clear" class="form-control datepicker" placeholder="{{ trans('latraining.choose_date') }}" value="{{ isset($model) ? get_date($model->date_clear) : '' }}"/>
                            </div>
                            <div class="col-2">
                                @can('interaction-history-clear-create')
                                    <button type="button" id="btn_save" onclick="save(event)" class="btn save" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <br>
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="full_name">{{ trans('latraining.creator') }}</th>
                    <th data-field="date_clear" data-align="center" data-width="10%">{{ trans('latraining.time_to_delete') }}</th>
                    <th data-field="date_created" data-align="center" data-width="10%">{{ trans('latraining.created_time') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.interaction_history_clear.getdata') }}',
        });

        function save(event) {
            let time = $('#date_clear').val();

            if(time.length <= 0){
                show_message('Mời nhập thời gian', 'error');
            }

            Swal.fire({
                title: 'Bạn đã chắc chắn?',
                text: "Sau khi thiết lập sẽ không thể xóa!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK!'
            }).then((result) => {
                if (result.value) {
                    let item = $('.save');
                    let oldtext = item.html();
                    item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
                    $('.save').attr('disabled',true);
                    event.preventDefault();

                    $.ajax({
                        url: "{{ route('backend.interaction_history_clear.save') }}",
                        type: 'post',
                        data: $("#form_save").serialize(),
                    }).done(function(data) {
                        item.html(oldtext);
                        $('.save').attr('disabled',false);

                        if (data && data.status == 'success') {

                            show_message(data.message, data.status);
                            $(table.table).bootstrapTable('refresh');
                        } else {
                            show_message(data.message, data.status);
                        }
                        return false;
                    }).fail(function(data) {
                        item.html(oldtext);
                        $('.save').attr('disabled',false);

                        show_message('Lỗi dữ liệu', 'error');
                        return false;
                    });
                }
            })
        }
    </script>
@endsection
