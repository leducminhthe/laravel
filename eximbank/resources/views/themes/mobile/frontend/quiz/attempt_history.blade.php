@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.history_summary'))

@section('content')
    <div class="container">
        <div class="row mt-2">
            <div class="col-12 p-1">
                <table class="tDefault table table-hover bootstrap-table text-nowrap table-bordered">
                    <thead>
                        <tr>
                            <th data-formatter="index_formatter" data-widht="10px" data-align="center">@lang('app.stt')</th>
                            <th data-formatter="info_formatter">@lang('app.info')</th>
                        </tr>
                    </thead>
                    {{--  <tbody>
                        @foreach ($attempts as $key => $attempt)
                            <tr>
                                <th>{{ $key + 1 }}</th>
                                <td>{{ trans('app.start_date')}}: {{ $attempt->start_date }}
                                    <br> {{ trans('app.end_date') }}: {{ $attempt->end_date }}
                                    <br> {{ trans('app.score') }}: {{ $attempt->grade }}
                                    <br> {{ trans('app.status') }}: {{ $attempt->status }}
                                    @if ($attempt->after_review == 1 || $attempt->closed_review == 1)
                                        <a href="{{ $attempt->review_link }}" class="btn text-white">Xem lại</a>
                                    @else
                                        <span class="text-muted">Không được xem</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>  --}}
                </table>
            </div>
        </div>
    </div>
@endsection
@section('footer')
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

    function info_formatter(value, row, index) {
        return "{{ trans('app.start_date'). ': ' }}" + row.start_date + "<br> {{ trans('app.end_date') .': ' }}" + row.end_date + "<br> {{ trans('app.score') .': ' }}" + row.grade + "<br> {{ trans('app.status') .': ' }} " + row.status + ((row.after_review == 1 || row.closed_review == 1) ? '<a href="'+ row.review_link +'" class="btn text-white float-right">Xem lại</a>' : '<span class="text-muted float-right">Không được xem</span>');
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.quiz_mobile.doquiz.data_attempt_history', ['quiz_id' => $quiz_id, 'part_id' => $part_id]) }}',
    });
</script>
@endsection
