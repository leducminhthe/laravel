@extends('layouts.backend')

@section('page_title', trans('lamenu.guide'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.guide'),
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
                <form class="form-inline w-100" id="form-search">
                    <input type="text" class="form-control" name="search" value="" placeholder="{{ trans('laguide.enter_name') }}">
                    <select name="type" id="type" class="form-control w-25">
                        <option value="" selected disabled>{{ trans('laguide.type') }}</option>
                        <option value="1">{{ trans('laguide.file') }}</option>
                        <option value="2">{{ trans('laguide.video') }}</option>
                        <option value="3">{{ trans('laguide.post') }}</option>
                    </select>
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('guide-create')
                        <a href="{{ route('backend.guide.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('guide-delete')
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" id="guide_table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('laguide.name') }}</th>
                    <th data-field="type" data-formatter="type" data-align="center">{{ trans('laguide.type') }}</th>
                    <th data-field="attach" data-formatter="attach">{{ trans('laguide.file_video_post_guide') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +' </a>';
        }
        function type(value, row, index) {
            if (row.type == 2) {
                return '<span>Video</span>';
            } else if(row.type == 3) {
                return '<span>{{ trans("lamenu.post") }}</span>';
            } else {
                return '<span>File</span>';
            }
        }
        function attach(value, row, index) {
            if (row.type == 3) {
                return '<span class="guide_posts">'+ row.attach +'</span>'
            }
            return row.attach;
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.guide.getdata') }}',
            remove_url: '{{ route('backend.guide.remove') }}'
        });
    </script>
@endsection
