@extends('layouts.backend')

@section('page_title', trans("lamenu.usermedal_setting"))

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
                'name' => trans("lamenu.usermedal_setting"),
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
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-formatter="index_formatter" data-align="center" data-width="5%">#</th>
                    <th data-field="user_medal">{{ trans("lamenu.usermedal_setting") }}</th>
                    <th data-field="datecreated" data-align="center" data-width="10%">{{ trans("laother.achieved_date") }}</th>
                    <th data-field="submedal_name">{{ trans("lamenu.user_level_setting") }}</th>
                    <th data-field="image_submedal" data-align="center" data-width="15%">{{ trans("laother.badge_image") }}</th>
                    <th data-field="submedal_rank" data-align="center" data-width="10%">{{ trans("laother.rating") }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.user_medal.getdata', ['user_id' => $user_id]) }}',
        });
    </script>
@endsection
