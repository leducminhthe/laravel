@extends('layouts.backend')

@section('page_title', trans('lamenu.online_course'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('lamenu.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.online.management') }}">{{ trans('lamenu.online_course') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.online.edit', ['id' => $course_id]) }}">{{ $page_title }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $type == 1 ? trans('latraining.student_notes', ['name' => $fullname]) : trans('latraining.student_reviews', ['name' => $fullname]) }} </span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row user_notes mx-2">
            @if ($type == 1)
                @if (count( $get_user_notes_evaluates ) == 0)
                    <div class="note col-12">
                        <center><h3>{{ trans('latraining.students_no_notes_in_course') }}</h3></center>
                    </div>
                @else
                    @foreach ($get_user_notes_evaluates as $item)
                        <div class="note col-12">
                            <span>{{$item->note}}</span>
                        </div>
                    @endforeach
                @endif
            @else
                <div class="col-12 mt-2 mb-3">
                    <h3>{{ trans('latraining.students_evaluate_course') }}:
                        {{ $get_rating !== null ? trans('latraining.num_star', ['num' => $get_rating->num_star]) : trans('latraining.no_evaluate_star_course') }}
                    </h3>
                    @if ($get_rating !== null)
                        <span>{{ trans('latraining.day') }}: {{$get_rating->created_at}}</span>
                    @endif
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 text-right act-btns mb-2">
                            <div class="pull-right">
                                <div class="btn-group">
                                    <a class="btn" href="{{route('module.online.export',['course_id' => $course_id,'id' => $id, 'user_type' => $user_type])}}"><i class="fa  fa-download"></i>{{ trans('labutton.export') }}</a>
                                </div>
                                @if($course->lock_course == 0)
                                <div class="btn-group">
                                    <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <table class="tDefault table table-hover text-nowrap" id="table-evaluate">
                        <thead>
                            <tr>
                                <th data-field="state" data-checkbox="true"></th>
                                {{-- <th data-field="fullname" data-width="20%">Tên học viên</th> --}}
                                <th data-field="content">{{ trans('latraining.comment') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            @endif
        </div>
        <br>
    </div>
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1);
        }
        var table_evaluate = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.get_content_evaluate',['course_id' => $course_id,'id' => $id, 'user_type' => $user_type]) }}',
            remove_url: '{{ route('module.online.content_evaluate_remove', ['course_id' => $course_id,'id' => $id]) }}',
            table: '#table-evaluate',
        });
    </script>
@endsection
