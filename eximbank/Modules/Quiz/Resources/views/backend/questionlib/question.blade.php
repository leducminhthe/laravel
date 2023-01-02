@extends('layouts.backend')

@section('page_title', trans('latraining.question'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.questionlib'),
                'url' => route('module.quiz.questionlib')
            ],
            [
                'name' => trans('latraining.question') .': '. $category->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

<style>
    table video {
        width: 50%;
        height: auto;
    }

    table img {
        width: 50% !important;
        height: auto !important;
    }

    .bootstrap-table .fixed-table-container .fixed-table-body{
        height: auto !important;
    }
    .name_question p {
        margin-bottom: 0px;
    }
</style>

@section('content')
    <div role="main">
        @if(isset($errors))

        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach

        @endif
        <div class="row">
            <div class="col-md-3">
                @include('quiz::backend.questionlib.filter_question')
            </div>
            <div class="col-md-9 text-right act-btns">
                <div class="pull-right">
                    @can('quiz-question-create')
                        <div class="btn-group">
                            <a class="btn" href="{{ download_template('mau_import_cau_hoi_v2.xlsx') }}">
                                <i class="fa fa-download"></i> {{ trans('labutton.import_template') }}
                            </a>
                            <button class="btn" id="import-plan" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                            </button>
                        </div>
                    @endcan
                    @can('quiz-question-approve')
                        <div class="btn-group">
                            <button class="btn status" data-status="1">
                                <i class="fa fa-check-circle"></i> {{trans("labutton.approve")}}
                            </button>
                            <button class="btn status" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> {{trans('labutton.deny')}}
                            </button>
                        </div>
                    @endcan
                    <div class="btn-group">
                        @can('quiz-question-create')
                            <button class="btn copy">
                                <i class="fa fa-copy"></i> {{trans("labutton.copy")}}
                            </button>
                            <a href="{{ route('module.quiz.questionlib.question.create', ['id' => $category->id]) }}" class="btn">
                                <i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}
                            </a>
                        @endcan
                        @can('quiz-question-delete')
                            <button class="btn" id="delete-item">
                                <i class="fa fa-trash"></i> {{ trans('labutton.delete') }}
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true" data-width="3%"></th>
                    <th data-field="index" data-formatter="index_formatter" data-width="3%" data-align="center"> # </th>
                    <th data-field="name" data-formatter="name_formatter" data-width="30%">{{ trans('backend.titles') }}</th>
                    <th data-field="difficulty" data-width="5%" data-align="center">Mức độ</th>
                    <th class="question_quiz" data-field="answers" data-formatter="answer_formatter">{{ trans('latraining.question') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{trans('latraining.status')}}</th>
                    <th data-field="view_question" data-align="center" data-formatter="view_question_formatter" data-width="5%">{{ trans('latraining.view_question') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.quiz.questionlib.import_question', ['id' => $category->id]) }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('latraining.question') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modal-copy" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans("latraining.copy_to_category") }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-3">{{ trans('app.category') }}</div>
                        <div class="col-9">
                            <select name="category_id" id="category_id" class="form-control select2" data-placeholder="{{ trans('app.category') }}">
                                <option value=""></option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    <button type="button" class="btn" id="copyQuestion"> <i class=""></i> {{ trans("labutton.copy") }}</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1);
        }

        function name_formatter(value, row, index) {
            var html = '<a class="name_question mb-1" href="'+ row.edit_url +'"><strong>'+ value +'</strong></a>' + (row.code ? '<p class="mb-0">('+row.code+')</p>' : '');
                html += row.approved_by + ' ' + row.time_approved +'<br>';
                html += row.created_by +' '+row.created_time;
            return html;
        }

        function answer_formatter(value, row, index) {
            var html = row.text_type + '<br>';
            html += '<ul class="list-group">';
            $.each(row.answers, function (i,e){
                var class_success = '';

                if (e.correct_answer > 0 || e.percent_answer > 0 || e.marker_answer) {
                    class_success = 'list-group-item-success';
                }

                html += '<li class="list-group-item '+class_success+'">';
                if (e.image_answer) {
                    html += '<img src="'+ e.image_answer +'" alt="" class="w-25 img-responsive"> <br>';
                }
                if (e.title) {
                    html += (e.title + (e.matching_answer ? ' '+ e.matching_answer : ''));
                }
                if (e.fill_in_correct_answer) {
                    html += ('<br>' + e.fill_in_correct_answer);
                }
                html += '</li>';
            });
            html += "</ul>";

            return html;
        }

        function status_formatter(value, row, index) {
            return value == 1 ? '<span class="text-success">{{ trans("backend.approved") }}</span>' : (value == 2 ? '<span class="text-warning">{{ trans("backend.not_approved") }}</span>': '<span class="text-danger">{{ trans("backend.deny") }}</span>');
        }

        function view_question_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="btn load-modal" data-url="'+ row.view_question +'"><i class="fa fa-eye"></i></a>';
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.questionlib.question.getdata', ['id' => $category->id]) }}',
            remove_url: '{{ route('module.quiz.questionlib.remove_question', ['id' => $category->id]) }}'
        });

        var ajax_status = "{{ route('module.quiz.questionlib.ajax_status', ['id' => $category->id]) }}";
        var ajax_copy_question = "{{ route('module.quiz.questionlib.copy_question', ['id' => $category->id]) }}";

        function success_submit(form) {
            $("#app-modal #myModal").modal('hide');
            table.refresh();
        }

        $('.copy').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 câu hỏi', 'error');
                return false;
            }

            $('#modal-copy').modal();
        });

        $('#copyQuestion').on('click', function(){
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var category_id = $('#category_id').val();

            var btnsubmit = $(this);
            var oldText = btnsubmit.text();
            var currentIcon = btnsubmit.find('i').attr('class');
            var exists = btnsubmit.find('i').length;
            if (exists>0)
                btnsubmit.find('i').attr('class', 'fa fa-spinner fa-spin');
            else
                btnsubmit.html('<i class="fa fa-spinner fa-spin"></i>'+oldText);

            btnsubmit.prop("disabled", true);

            $.ajax({
                url: ajax_copy_question,
                type: 'post',
                data: {
                    ids: ids,
                    category_id: category_id
                }
            }).done(function(data) {

                if (data.redirect) {
                    setTimeout(function () {
                        window.location = data.redirect;
                    }, 1000);
                    return false;
                }

                $('#category_id').val('');
                $('#modal-copy').modal('hide');
                $(table.table).bootstrapTable('refresh');

                if (exists>0)
                    btnsubmit.find('i').attr('class', currentIcon);
                else
                    btnsubmit.html(oldText);
                btnsubmit.prop("disabled", false);

                return false;
            }).fail(function(data) {

                if (exists>0)
                    btnsubmit.find('i').attr('class', currentIcon);
                else
                    btnsubmit.html(oldText);
                btnsubmit.prop("disabled", false);


                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        })
    </script>
    <script src="{{ asset('styles/module/quiz/js/question.js') }}"></script>
@endsection
