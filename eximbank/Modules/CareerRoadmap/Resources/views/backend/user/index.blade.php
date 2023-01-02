@extends('layouts.backend')

@section('page_title', trans('laprofile.career_roadmap'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.user'),
                'url' => route('module.backend.user')
            ],
            [
                'name' => $full_name,
                'url' => route('module.backend.user.edit',['id'=>$user->id])
            ],
            [
                'name' => trans('laprofile.career_roadmap'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        @include('user::backend.layout.menu')

        <div class="table-responsive">
            <table class="table">
                <thead class="bg-danger text-white text-bold">
                <tr>
                    <th width="5%">#</th>
                    <th>@lang('laprofile.title')</th>
                    <th width="20%">{{ trans('career.view') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($roadmaps as $roadmap)
                    <tr class="bg-primary text-white">
                        <th colspan="3">{{ $roadmap->name }}</th>
                    </tr>
                    @php
                        $sub_titles = $roadmap->getTitles();
                    @endphp
                    @foreach($sub_titles as $index => $sub_title)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ str_repeat('-- ', $sub_title->level) . $sub_title->title->name }}</td>
                            <td><a href="javascript:void(0)" class="btn view-career" data-id="{{ $sub_title->title->id }}" data-name="{{ $sub_title->title->name }}"><i class="fa fa-eye"></i> @lang('career.view')</a></td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="course-modal" tabindex="-1" role="dialog" aria-labelledby="course-modal-label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title title-name" id="course-modal-label"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table bootstrap-table">
                        <thead>
                        <tr>
                            <th data-width="5%">#</th>
                            <th data-field="subject_code" data-width="10%">@lang('laprofile.subject_code')</th>
                            <th data-field="subject_name">@lang('laprofile.subject')</th>
                            <th data-field="title_name" data-formatter="result_formatter" class="td-title-name"></th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">@lang('labutton.close')</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function result_formatter(value, row, index) {
            if (row.result == 1) {
                return '<i class="fa fa-check"></i>';
            }

            return '<i class="fa fa-times"></i>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.career_roadmap.frontend.get_courses', [0]) }}',
            locale: '{{ App::getLocale() }}',
        });

        $('.table').on('click', '.view-career', function () {
            let title_id = $(this).data('id');
            let title_name = $(this).data('name');
            let new_url = "/admin-cp/user/career-roadmap/<?php echo $user_id ?>/courses/" + title_id;
            table.refresh({'url': new_url});

            $('.title-name').html(title_name);
            $('.td-title-name').html('<div class="th-inner ">'+ title_name +'</div><div class="fht-cell"></div>');
            $('#course-modal').modal();
        });
    </script>

@endsection
