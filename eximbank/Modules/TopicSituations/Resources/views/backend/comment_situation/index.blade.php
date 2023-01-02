@extends('layouts.backend')

@section('page_title', trans('lahandle_situations.comment_situation'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.situations_proccessing'),
                'url' => route('module.topic_situations')
            ],
            [
                'name' => $model->name,
                'url' => route('module.situations',['id' => $model->id])
            ],
            [
                'name' => $situation->name . ': ' .trans('lahandle_situations.comment_situation'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12 form-inline">
                <form class="form-inline form-search-user w-100 mb-3" id="form-search">
                    <div class="w-24">
                        <input type="text" name="search" value="" class="form-control w-100" placeholder="{{ trans('lahandle_situations.enter_code_name') }}">
                    </div>
                    <div class="w-24">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('lahandle_situations.title') }} --"></select>
                    </div>
                    <div class="w-24">
                        <select name="unit" class="form-control load-unit" data-placeholder="-- {{ trans('lahandle_situations.unit') }} --"></select>
                    </div>
                    <div class="w-24">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- {{ trans('lahandle_situations.area') }} --"></select>
                    </div>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="fullname" data-width="20%">{{ trans('lahandle_situations.user_comment') }}</th>
                    <th data-field="unit_name">{{ trans('lahandle_situations.work_unit') }}</th>
                    <th data-field="unit_manager">{{ trans('lahandle_situations.unit_manager') }}</th>
                    <th data-field="title_name" data-width="15%">{{ trans('lahandle_situations.title') }}</th>
                    <th data-field="comment" data-width="50%">{{ trans('lahandle_situations.comment') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.get.comment.situations',['id' => $topic_id, 'situation' => $situation->id]) }}',
        });

    </script>
@endsection
