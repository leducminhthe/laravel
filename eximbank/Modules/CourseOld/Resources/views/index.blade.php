{{-- @extends('layouts.backend')

@section('page_title', trans('backend.course_old'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('lamenu.training_organizations') }}
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.course_old') }}</span>
        </h2>
    </div>
@endsection

@section('content') --}}
    <div role="main">
{{--         @if(isset($errors))
            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
            @php
                session()->forget('errors');
            @endphp
        @endif--}}
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
            <div class="col-md-2 form-inline">
                @include('courseold::filter')
            </div>
            <div class="col-md-10 text-right act-btns mt-2">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn" href="{{ download_template('mau_import_khoa_hoc_cu.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                        @can('course-old-import')
                            <button class="btn" id="import-plan" type="button">
                                <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                            </button>
                        @endcan
                        @can('course-old-export')
                            <a class="btn" href="javascript:void(0)" id="export-course-old"><i class="fa fa-download"></i> {{ trans('labutton.export') }}</a>
                        @endcan
                    </div>
                    <div class="btn-group">
                        @can('course-old-delete')
                            <button class="btn" id="delete-item" ><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="user_code" >{{ trans('backend.employee_code') }}</th>
                    <th data-sortable="true" data-field="full_name" data-formatter="fullName_formatter">{{ trans('backend.fullname') }}</th>
                    <th data-sortable="true" data-field="unit" >{{ trans('backend.direct_units') }}</th>
                    <th data-sortable="true" data-field="title">{{ trans('latraining.title') }}</th>
                    <th data-sortable="true" data-field="course_code">{{ trans('latraining.course_code') }}</th>
                    <th data-sortable="true" data-field="course_name">{{ trans('latraining.course_name') }}</th>
                    <th data-sortable="true" data-field="start_date">{{ trans('latraining.start_date') }}</th>
                    <th data-sortable="true" data-field="end_date">{{ trans('latraining.end_date') }}</th>
                    <th data-sortable="true" data-field="course_type" data-formatter="course_type_formatter">{{ trans('backend.type_course') }}</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <form action="{{ route('module.courseold.import') }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('backend.course_old') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                            <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        function fullName_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="text-success view-detail" data-id="'+row.id+'">'+ value +'</a>';
        }
        function course_type_formatter(value, row, index) {
            return value==1?'Online':'{{ trans("latraining.offline") }}';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.courseold') }}',
            remove_url: '{{ route('module.courseold.remove') }}',

        });

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        $('#export-course-old').on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{route('module.courseold.export')}}?'+form_search;
        })

        $(document).on('click','.view-detail', function() {
            let id = $(this).data('id');
            let btn = $(this);
            let text = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i>').prop("disabled", true);
            $.ajax({
                url: base_url+'/admin-cp/courseold/show/'+id,
                type: 'get',
                data: {},
                dataType:'html'
            }).done(function(result) {
                $("#app-modal").html(result);
                $("#app-modal #modal-detail").modal();
                btn.html(text).prop("disabled", false);
            }).fail(function(result) {
                show_message('Lỗi hệ thống', 'error');
                btn.html(text).prop("disabled", false);
                return false;
            });
        });
    </script>

{{-- @endsection --}}
