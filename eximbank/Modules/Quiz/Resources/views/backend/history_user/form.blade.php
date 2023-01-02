@extends('layouts.backend')

@section('page_title', 'Lịch sử thi tuyển thí sinh nội bộ')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.history') }}
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.quiz.history_user') }}">Lịch sử thi tuyển thí sinh nội bộ</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ \App\Models\Profile::fullname($user_id) }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-7">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" class="form-control" placeholder="{{ data_locale('Nhập mã / tên kỳ thi', 'Enter the quiz name / code') }}">
                    <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }} </button>
                </form>
            </div>
        </div>
        <p></p>
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
            <tr>
                <th data-field="quiz_code">{{ trans('backend.quiz_code') }}</th>
                <th data-field="quiz_name">{{ trans('backend.quiz_name') }}</th>
                <th data-field="part_name">{{ trans('latraining.part') }}</th>
                <th data-field="attempt" data-align="center">Lần thi</th>
                <th data-field="time_start">{{ trans('backend.time_start') }}</th>
                <th data-field="time_end">{{ trans('backend.end_time') }}</th>
                <th data-field="score" data-with="5%" data-align="center" >{{ trans('backend.score') }}</th>
                <th data-field="result" data-align="center" data-width="10%">{{ trans('backend.result') }}</th>
                <th data-field="review_url" data-formatter="review_quiz_formatter" data-align="center" data-width="5%">{{ trans('latraining.review') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function review_quiz_formatter(value, row, index){
            return '<a href="'+ row.review_url +'" class="btn" > <i class="fa fa-eye"></i> </a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.history_result_user.getdata', ['user_id' => $user_id]) }}',
        });
    </script>
@endsection
