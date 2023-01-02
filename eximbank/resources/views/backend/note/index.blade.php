@extends('layouts.backend')

@section('page_title', trans('lamenu.note'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.note'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline form-search-user w-100 mb-3" id="form-search">
                    <div class="w-30">
                        @include('backend.form_choose_unit')
                    </div>
                    <div class="w-30">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('lanote.title') }} --"></select>
                    </div>
                    <input type="text" class="form-control" name="search" value="" placeholder="{{ trans('lanote.enter_code_name_user') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" id="contact_table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="fullname" data-width="15%">{{ trans('lanote.name') }}</th>
                    <th data-field="date_time" data-width="10%">{{ trans('lanote.created_at') }}</th>
                    <th data-field="content">{{ trans('lanote.note') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.note.getdata') }}',
        });
    </script>
@endsection
