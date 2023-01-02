@extends('layouts.backend')

@section('page_title', trans('lamenu.permission_group'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('laother.list_permission') }}
        </h2>
    </div>
@endsection

@section('content')
    <div role="main" id="permission-group">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" class="form-control" value="">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>

                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">

                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="num" data-width="5%" data-formatter="num_formatter" data-align="center">STT</th>
                    <th data-field="name">{{ trans('laother.permission_name') }}</th>
                    <th data-field="permission" data-width="20%" data-formatter="permission_formatter" data-align="center">{{ trans('lamenu.permission') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">

        function num_formatter(value, row, index) {
            return (index + 1);
        }

        function permission_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'"><i class="fa fa-cogs"></i> {{ trans("lamenu.permission") }}</a>';
        }

        $(function () {

            var table = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: "{{ route('backend.permission.list_permisstion.getdata') }}",
            });

        });
    </script>
@stop
