@extends('layouts.backend')

@section('page_title', trans('lamenu.information_edit'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.information_edit'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-5">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('backend.enter_code_name_block')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-7 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="code">{{trans('backend.employee_code')}}</th>
                    <th data-field="name">{{trans('backend.fullname')}}</th>
                    <th data-field="quiz_code">{{trans('backend.quiz_code')}}</th>
                    <th data-field="quiz_name">{{ trans('backend.quiz_name') }}</th>
                    <th data-field="title">{{ trans('laprofile.heading') }}</th>
                    <th data-field="content">{{ trans('backend.content') }}</th>
                    <th data-field="created_at2" data-align="center">{{ trans('backend.time') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.user_second_note.getdata') }}',
            remove_url: '{{ route('module.quiz.remove_user_second_note') }}'
        });

    </script>
@endsection
