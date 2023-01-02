{{-- @extends('layouts.backend')

@section('page_title', 'Gộp chuyên đề')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">{{trans('backend.merge_subject')}}</span>
        </h2>
    </div>
@endsection

@section('content') --}}

    <div role="main">
        @if(isset($errors))

            @foreach($errors as $error)
                <div class="alert alert-danger">{!! $error !!}</div>
            @endforeach
        {{session()->forget('errors')}}
        @endif
        <div class="row">
            <div class="col-md-4">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder="{{trans('backend.merged_subject_search')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{trans('labutton.search')}}</button>
                </form>
            </div>
            <div class="col-md-8 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group" >
                        <button id="importMergesubject" type="button" class="btn" >
                            <i class="fa fa-upload"> </i> {{ trans('labutton.import') }}
                        </button>
                        <a class="btn" href="{{ download_template('mau_import_gop_chuyen_de.xlsx') }}"><i class="fa fa-download"> {{ trans('labutton.import_template') }} </i></a>
                    </div>
                    <div class="btn-group">
                        @can('mergesubject-approved')
                            <button class="btn approve" data-status="1"><i class="fa fa-check-circle"></i> {{trans('labutton.approve')}}</button>
                            <button class="btn approve" data-status="0"><i class="fa fa-exclamation-circle"></i> {{trans('labutton.deny')}}</button>
                        @endcan
                    </div>
                    <div class="btn-group">
                        @can('mergesubject-create')
                            <a href="{{ route('module.mergesubject.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{trans('labutton.add_new')}}</a>
                        @endcan
                        @can('mergesubject-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{trans('labutton.delete')}}</button>
                        @endcan
                    </div>
                    @can('merge-subject-watch-log')
                        <a class="btn" href="{{route('module.mergesubject.logs')}}"><i class="fa fa-check-circle"></i> {{trans('labutton.view_logs')}}</a>
                    @endcan
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-field="old_subject" >{{trans('backend.merge_subject_origin')}}</th>
                    <th data-sortable="true" data-field="name_subject_new" data-formatter="name_formatter">{{trans('backend.merge_subject_new')}}</th>
                    <th data-width="50px" data-align="center" data-field="subject_old_complete">{{trans('backend.merge_subject_condition_finish')}}</th>
                    <th data-width="8%" data-field="number_merge_subject"  data-align="center">{{trans('backend.merge_subject_number')}}</th>
                    <th data-width="8%" data-field="number_merge_completed"  data-align="center">{{trans('backend.merged_subject_number')}}</th>
                    <th data-width="8%" data-field="status" data-align="center">{{trans('latraining.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.mergesubject.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('mergesubject::mergesubject.import_merge_subject') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="unit_id" value=" ">
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
    <script type="text/javascript">
        // function number_merge_subject(value, row,index) {
        //     return `<span class="text-danger">${row.number_merge_subject}</span>`;
        // }
        // function number_merge_completed(value,row,index) {
        //     return `<span class="text-success">${row.number_merge_completed}</span>`;
        // }
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name_subject_new+'</a>';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.mergesubject.getData') }}',
            remove_url: '{{ route('module.mergesubject.remove') }}'
        });
        $('#importMergesubject').on('click', function() {
            $('#modal-import').modal();
        });
    </script>
    <script src="{{ asset('styles/module/mergesubject/js/mergesubject.js?v=1.2') }}"></script>
{{-- @endsection --}}
