{{-- @extends('layouts.backend')

@section('page_title', 'Chương trình khung')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('lamenu.training') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{trans('backend.trainingroadmap')}}</span>
        </h2>
    </div>
@endsection
@section('content') --}}
    <div role="main">
        @if(isset($errors))
        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach
        @endif
        <div class="row">
            <div class="col-md-6 mb-3">
                @include('trainingroadmap::index.filter')
            </div>
            <div class="col-md-6 text-right">
                <div class="pull-right">
                    <button class="btn copy">
                        <i class="fa fa-copy"></i> &nbsp;{{trans("labutton.copy")}}
                    </button>
                    @can('training-roadmap-create')
                        <div class="btn-group">
                            <a class="btn" href="{{ route('module.trainingroadmap.export_roadmap') }}" id="export-excel">
                                <i class="fa fa-download"></i> {{ trans('labutton.export') }}
                            </a>
                        </div>
                        <div class="btn-group">
                            <a class="btn" href="{{ download_template('mau_import_chuong_trinh_khung.xlsx') }}">
                                <i class="fa fa-download"></i> {{trans('labutton.import_template')}}
                            </a>
                            <button class="btn" id="import-plan" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                            </button>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="2%">#</th>
                    <th data-field="code">{{trans('backend.title_code')}}</th>
                    <th data-field="name" data-formatter="name_formatter">{{trans('backend.title_name')}}</th>
                    <th data-field="num_subject">{{trans('backend.number_modules')}}</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <form action="{{ route('module.trainingroadmap.detail.import') }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">IMPORT {{trans('backend.trainingroadmap')}}</h5>
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

        <div class="modal fade" id="modal-copy" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans("backend.copy") }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-3">{{ trans('laother.source_title') }}</div>
                            <div class="col-9">
                                @php
                                    $titles = \App\Models\Categories\Titles::query()
                                        ->whereIn('id', function ($sub){
                                            $sub->select(['title_id'])
                                                  ->from('el_trainingroadmap')
                                                  ->pluck('title_id')
                                                  ->toArray();
                                        })->get();
                                @endphp
                                <select name="title_old" id="title_old" class="form-control select2" data-placeholder="{{ trans('laother.source_title') }}">
                                    <option value=""></option>
                                    @foreach($titles as $title)
                                        <option value="{{ $title->id }}">{{ $title->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-3">{{ trans('laother.target_title') }}</div>
                            <div class="col-9">
                                <select name="title_new" id="title_new" class="form-control load-title" data-placeholder="{{ trans('laother.target_title') }}">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        <button type="button" class="btn" id="copyTitle">{{ trans("labutton.copy") }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        function index_formatter(value, row, index) {
            return (index+1);
        }
        function name_formatter(value, row, index) {
            return '<a href="'+ row.title_url +'"> '+row.name+' </a>';
        }
        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });
        $('.copy').on('click', function() {
            $('#modal-copy').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.trainingroadmap.getdata') }}',
        });

        $('#copyTitle').on('click', function () {
            var title_old = $('#title_old option:selected').val();
            var title_new = $('#title_new option:selected').val();

            $.ajax({
                url: '{{ route('module.trainingroadmap.ajax_check_training_roadmap') }}',
                type: 'post',
                data: {
                    title_old: title_old,
                    title_new: title_new
                }
            }).done(function(data) {
                if (data.status == 'error'){
                    show_message(data.message, data.status);
                    window.location = '';
                    return false;
                }else {
                    Swal.fire({
                        title: '',
                        text: data.message,
                        type: data.status,
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ trans("laother.agree") }}!',
                        cancelButtonText: '{{ trans("labutton.cancel") }}!',
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '{{ route('module.trainingroadmap.ajax_copy') }}',
                                type: 'post',
                                data: {
                                    title_old: title_old,
                                    title_new: title_new
                                }
                            }).done(function(data) {
                                show_message(data.message, data.status);
                                window.location = '';
                                return false;
                            }).fail(function(data) {
                                show_message('Lỗi hệ thống', 'error');
                                return false;
                            });
                        }
                    });
                }
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });
    </script>
{{-- @endsection --}}
