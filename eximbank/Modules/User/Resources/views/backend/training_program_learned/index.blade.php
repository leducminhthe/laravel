@extends('layouts.backend')

@section('page_title', trans('laprofile.training_program_learned'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.user'),
                'url' => route('module.backend.user')
            ],
            [
                'name' => $full_name,
                'url' => route('module.backend.user.edit',['id' => $user_id])
            ],
            [
                'name' => trans('laprofile.training_program_learned'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    @if($user_id)
        @include('user::backend.layout.menu')
    @endif
    <div role="main">
        <div class="row">
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    @if(!\App\Models\Permission::isUnitManager())
                    <div class="btn-group">
                        <a href="{{ route('module.backend.training_program_learned.create', ['user_id' => $user_id]) }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="code" data-width="5%">{{ trans('laprofile.employee_code') }}</th>
                <th data-field="fullname" data-width="20%" data-formatter="fullname_formatter">{{ trans('laprofile.employee_name') }}</th>
                <th data-field="email">{{ trans('laprofile.employee_email') }}</th>
                <th data-field="training_program">{{ trans('laprofile.training_program') }}</th>
                <th data-field="time" data-align="center">{{ trans('laprofile.time') }}</th>
                <th data-field="note">{{ trans('laprofile.note') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function fullname_formatter(value, row, index) {
            return '<a href="' + row.edit_url + '">' + row.fullname + '</a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.training_program_learned.getdata', ['user_id' => $user_id]) }}',
            remove_url: '{{ route('module.backend.training_program_learned.remove', ['user_id' => $user_id]) }}',
        });

    </script>
@endsection
