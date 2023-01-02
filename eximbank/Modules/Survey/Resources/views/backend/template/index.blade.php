@extends('layouts.backend')

@section('page_title', trans('lasurvey.survey_template'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.survey'),
                'url' => route('module.survey.index')
            ],
            [
                'name' => trans('lasurvey.survey_template'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-4">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder='{{trans("lasurvey.enter_template_name")}}'>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-8 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('survey-template-create')
                            <button class="btn copy">
                                <i class="fa fa-copy"></i> {{ trans('labutton.copy') }}
                            </button>
                            <a href="{{ route('module.survey.template.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('survey-template-delete')
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
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-formatter="name_formatter">{{trans('lasurvey.template_name')}}</th>
                    <th data-field="course" data-width="10%" data-align="center">{{ trans('latraining.type') }}</th>
                    <th data-field="created_by" data-width="20%" data-formatter="created_by_formatter">{{ trans('lasurvey.created_by') }}</th>
                    <th data-field="updated_by" data-width="20%" data-formatter="updated_by_formatter">{{trans('lasurvey.update_by')}}</th>
                    <th data-field="review" data-width="5%" data-formatter="review_formatter" data-align="center">{{ trans('lasurvey.review_template') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
        }

        function created_by_formatter(value, row, index) {
            return row.created_by;
        }

        function updated_by_formatter(value, row, index) {
            return row.updated_by;
        }

        function review_formatter(value, row, index) {
            return '<a href="'+ row.review +'" class="btn"> <i class="fa fa-eye"></i> </a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.survey.template.getdata') }}',
            remove_url: '{{ route('module.survey.template.remove') }}'
        });

        $('.copy').on('click', function () {
            let item = $(this);
            let oldtext = item.html();
            let id = item.data('id');
            item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');

            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 mẫu đánh giá', 'error');
                return false;
            }

            $.ajax({
                url: '{{ route('module.survey.template.copy_template') }}',
                type: 'post',
                data: {
                    ids: ids,
                }
            }).done(function(data) {
                item.html(oldtext);
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        });
    </script>
@endsection
