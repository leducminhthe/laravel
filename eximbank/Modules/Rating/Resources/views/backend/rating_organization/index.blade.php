{{-- @extends('layouts.backend')

@section('page_title', trans('lamenu.rating_organization'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            Đánh giá hiệu quả đào tạo <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('lamenu.rating_organization') }}</span>
        </h2>
    </div>
@endsection

@section('content') --}}

    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder='Nhập tên kỳ đánh giá'>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('rating-levels-create')
                    <div class="btn-group">
                        <button class="btn publish" data-status="1">
                            <i class="fa fa-check-circle"></i> &nbsp;{{ trans('labutton.enable') }}
                        </button>
                        <button class="btn publish" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('labutton.disable') }}
                        </button>
                    </div>
                    @endcan
                    <div class="btn-group">
                        @can('rating-levels-create')
                        <a href="{{ route('module.rating_organization.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('rating-levels-delete')
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
                    <th data-field="status" data-align="center" data-width="5%" data-formatter="status_formatter">{{ trans('latraining.status') }}</th>
                    <th data-field="name" data-formatter="name_formatter">Tên kỳ đánh giá</th>
                    <th data-field="course" data-align="center" data-formatter="course_formatter">{{ trans('lamenu.course') }}</th>
                    <th data-field="count_user" data-align="center">{{trans('backend.join')}} / {{trans('backend.object')}}</th>
                    <th data-field="setting" data-align="center" data-formatter="setting_formatter">{{ trans('latraining.settings') }}</th>
                    <th data-field="result" data-align="center" data-formatter="result_formatter">{{ trans('latraining.result') }}</th>
                    <th data-field="register" data-align="center" data-formatter="register_formatter">{{ trans('latraining.student') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'" class="text-primary">'+ row.name +'</a>';
        }

        function course_formatter(value, row, index) {
            return '<a href="" title="'+ row.list_course +'">'+ row.course +'</a>';
        }

        function register_formatter(value, row, index) {
            if(row.register_url){
                return '<a href="'+ row.register_url +'"> <i class="fa fa-user"></i></a>';
            }
            return '';
        }

        function setting_formatter(value, row, index) {
            if(row.setting_url){
                return '<a href="'+ row.setting_url +'"> <i class="fa fa-cog"></i></a>';
            }
            return 'Mời thêm nhân viên';
        }

        function result_formatter(value, row, index) {
            if(row.result_url){
                return '<a href="'+ row.result_url +'"> <i class="fa fa-eye"></i></a>';
            }
            return '';
        }

        function status_formatter(value, row, index){
            return row.status == 1 ? 'Bật' : 'Tắt';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.rating_organization.getdata') }}',
            remove_url: '{{ route('module.rating_organization.remove') }}'
        });

        $('.publish').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 kỳ đánh giá', 'error');
                return false;
            }

            var btnsubmit = $(this);
            var oldText = btnsubmit.text();
            var currentIcon = btnsubmit.find('i').attr('class');
            var exists = btnsubmit.find('i').length;
            if (exists>0)
                btnsubmit.find('i').attr('class', 'fa fa-spinner fa-spin');
            else
                btnsubmit.html('<i class="fa fa-spinner fa-spin"></i>'+oldText);

            btnsubmit.prop("disabled", true);

            $.ajax({
                url: '{{ route('module.rating_organization.open') }}',
                type: 'post',
                data: {
                    ids: ids,
                    status: status,
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');

                if (exists>0)
                    btnsubmit.find('i').attr('class', currentIcon);
                else
                    btnsubmit.html(oldText);
                btnsubmit.prop("disabled", false);

                return false;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');

                if (exists>0)
                    btnsubmit.find('i').attr('class', currentIcon);
                else
                    btnsubmit.html(oldText);
                btnsubmit.prop("disabled", false);

                return false;
            });
        });

    </script>
{{-- @endsection --}}
