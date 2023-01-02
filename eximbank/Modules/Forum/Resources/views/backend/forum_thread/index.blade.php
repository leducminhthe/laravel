@extends('layouts.backend')

@section('page_title', trans('laforums.approve'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.forum'),
                'url' => route('module.forum.category')
            ],
            [
                'name' => $cate->name,
                'url' => route('module.forum', ['cate_id' => $cate->id])
            ],
            [
                'name' => trans('laforums.approve'). ': '. $forum->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('laforums.enter_name_forum')}}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('forum-approve-post')
                    <div class="btn-group">
                        <!-- @can('forum_thread-create')
                            <a href="{{ route('module.forum.thread.create', ['cate_id' => $cate->id,'forum_id' => $forum->id]) }}" class="btn">
                                <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                            </a>
                        @endcan -->
                        @can('forum-approve-post')
                            <button class="btn publish" ><i class="fa fa-check-square"></i>&nbsp;{{ trans('labutton.approve') }}</button>
                        @endcan
                        @can('forum_thread-remove')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                    @endcan
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="2%">#</th>
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="title" data-formatter="title_formatter" >{{ trans('laforums.title_thread') }}</th>
                    <th data-field="created_at2" data-align="center" data-width="20%">{{ trans('laforums.created_at') }}</th>
                    <th data-field="status" data-align="center" data-width="5%" data-formatter="status_formatter">{{ trans('laforums.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">

        function title_formatter(value, row, index) {
            return '<a href="'+ row.edit_thread +'">'+ row.title +'</a>';
        }

        function index_formatter(value, row, index) {
            return (index+1);
        }
        function status_formatter(value, row, index)
        {
            return value == 1 ? '<span class="text-success">{{ trans("backend.approve") }}</span>' : '<span class="text-warning">{{ trans("backend.not_approved") }}</span>';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.forum.getdatathread', ['cate_id' => $cate->id, 'forum_id' => $forum->id]) }}',
            remove_url: '{{ route('module.forum.thread.remove',['cate_id' => $cate->id, 'forum_id' => $forum->id]) }}'
        });

        var ajax_save_status = "{{ route('module.forum.save_status', ['cate_id' => $cate->id, 'forum_id' => $forum->id]) }}";
    </script>
<script type="text/javascript" src="{{ asset('styles/module/forum/js/forum.js') }}"></script>
@endsection
