{{-- @extends('layouts.backend')

@section('page_title', 'Đánh giá sau khóa học')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            Đánh giá hiệu quả đào tạo <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Mẫu đánh giá</span>
        </h2>
    </div>
@endsection

@section('content') --}}

    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder='Nhập mã/tên mẫu'>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('rating-template-create')
                            <button class="btn copy">
                                <i class="fa fa-copy"></i> {{ trans('labutton.copy') }}
                            </button>
                            <button class="btn" data-toggle="modal" data-target="#createTemplate">
                                <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                            </button>
                        @endcan
                        @can('rating-template-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
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
                    <th data-field="code">{{ trans('backend.code') }}</th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.form_name') }}</th>
                    <th data-formatter="type_formatter">{{ trans('backend.type') }}</th>
                    <th data-field="created_by">{{ trans('backend.created_by') }}</th>
                    <th data-field="time_created" data-width="5%" data-align="center">Thời gian tạo</th>
                    <th data-field="updated_by">{{ trans('backend.update_by') }}</th>
                    <th data-field="time_updated" data-width="5%" data-align="center">Thời gian sửa</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="createTemplate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('labutton.add_new') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="{{ route('module.rating.template.create', ['teaching_organization' => 1]) }}" class="btn">
                            <i class="fa fa-plus-circle"></i> {{ trans('latraining.teaching_organization') }}
                        </a>
                        <a href="{{ route('module.rating.template.create') }}" class="btn">
                            <i class="fa fa-plus-circle"></i> Mô hình Kirkpatrick
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
        }

        function type_formatter(value, row, index){
            return row.teaching_organization == 1 ? "{{ trans('latraining.teaching_organization') }}" : 'MH Kirkpatrick';
        }

        function export_formatter(value, row, index) {
            let str = '';
            if (row.export_word) {
                str += ' <a href="'+ row.export_word +'" class="btn"><i class="fa fa-file-word"></i></a>';
            }
            if (row.export_pdf) {
                str += '<a href="'+ row.export_pdf +'" class="btn"><i class="fa fa-file-pdf"></i></a>';
            }

            return str;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.rating.template.getdata') }}',
            remove_url: '{{ route('module.rating.template.remove') }}'
        });

        $('.copy').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 mẫu đánh giá', 'error');
                return false;
            }

            $.ajax({
                url: '{{ route('module.rating.template.copy') }}',
                type: 'post',
                data: {
                    ids: ids,
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        });
    </script>
{{-- @endsection --}}
