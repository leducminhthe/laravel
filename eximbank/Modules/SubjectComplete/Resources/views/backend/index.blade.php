{{-- @extends('layouts.backend')

@section('page_title', trans('backend.subject_complete'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">{{trans('backend.subject_complete')}}</span>
        </h2>
    </div>
@endsection

@section('content') --}}

    <div role="main">
        @if(isset($notifications))
            @foreach($notifications as $notification)
                @if(@$notification->data['messages'])
                    @foreach($notification->data['messages'] as $message)
                        <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}: {!! $message !!}</div>
                    @endforeach
                @else
                    <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}</div>
                @endif
                @php
                    $notification->markAsRead();
                @endphp
            @endforeach
        @endif
        <div class="row">
            <div class="col-md-6">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder="{{trans('backend.merged_subject_search')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{trans('labutton.search')}}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    @can('subjectcomplete-import')
                        <div class="btn-group">
                            <a class="btn" href="{{ download_template('mau_import_hoan_thanh_qua_trinh_dao_tao.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                            <button class="btn" id="import-plan" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                            </button>
                        </div>
                    @endcan
                    @can('subjectcomplete-approved')
                        <a class="btn" href="{{route('module.subjectcomplete.approve')}}"><i class="fa fa-check-circle"></i> {{trans('labutton.approve')}}</a>
                    @endcan
                    @can('subjectcomplete-watch-log')
                        <a class="btn" href="{{route('module.subjectcomplete.logs')}}"><i class="fa fa-check-circle"></i> {{trans('labutton.view_logs')}}</a>
                    @endcan
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-sortable="true" data-align="center" data-formatter="stt_formatter" data-width="3%">{{ trans('latraining.stt') }}</th>
                    <th data-field="code" data-width="5%">{{ trans('backend.employee_code') }}</th>
                    <th data-field="fullname" data-formatter="fullname_formatter" data-width="20%">{{ trans('backend.employee_name') }}</th>
                    <th data-field="email" >{{ trans('backend.employee_email') }}</th>
                    <th data-field="title_name">{{ trans('latraining.title') }}</th>
                    <th data-field="unit_name" data-formatter="unit_formatter" data-with="5%">{{ trans('backend.work_unit') }}</th>
                    <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.subjectcomplete.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('subjectcomplete::subjectcomplete.import_subject_complete') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="unit_id" value=" ">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        <div class="form-group row mt-2">
                            <div class="col-md-4">
                                <label for="">Chọn khóa chính <span class="text-danger">(*)</span></label>
                            </div>
                            <div class="col-md-8">
                                <label class="radio-inline">
                                    <input type="radio" name="type_import" class="mr-1" value="1" checked>
                                    {{ trans('latraining.employee_code') }}
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="type_import" class="mr-1" value="2">
                                    Username
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="type_import" class="mr-1" value="3">
                                    Email
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        function stt_formatter(value, row, index) {
            return (index + 1);
        }
        function fullname_formatter(value, row, index) {
            return '<a href="' + row.edit_url + '">' + row.lastname + ' ' + row.firstname + '</a>';
        }

        function unit_formatter(value, row, index) {
            return row.unit_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.unit_url+'"> <i class="fa fa-info-circle"></i></a>';
        }

        function area_formatter(value, row, index) {
            return row.area_name ? row.area_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.area_url+'"> <i class="fa fa-info-circle"></i></a>' : '';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.subjectcomplete.getData') }}',
        });
        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });
    </script>

{{-- @endsection --}}
