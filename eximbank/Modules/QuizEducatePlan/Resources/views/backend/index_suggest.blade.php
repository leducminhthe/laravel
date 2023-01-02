@extends('layouts.backend')

@section('page_title', trans('latraining.suggest_quiz_for_unit'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.suggest_quiz_for_unit'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12">
                <form id="form-search">
                    <div class="form-row align-items-center">
                        <div class="col-sm-2 my-1">
                            <input type="text" name="search" value="" class="form-control" autocomplete="off" placeholder="{{ trans('backend.code_name_course') }}">
                        </div>
                        <div class="col-sm-2 my-1">
                            <input name="start_date" type="text" class="datepicker form-control" placeholder="{{ trans('latraining.start_date') }}" autocomplete="off">
                        </div>
                        <div class="col-sm-2 my-1">
                            <input name="end_date" type="text" class="datepicker form-control" placeholder="{{ trans('latraining.end_date') }}" autocomplete="off">
                        </div>
                        <button type="submit" class="btn ml-2"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="javascript:void (0);" class="btn add_new"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table
        table-hover bootstrap-table"
               id="table-course-educate-plan">
            <thead>
                <tr>
                    <th data-field="state"
                        data-width="5%" data-checkbox="true"></th>
                    <th data-field="name" data-sortable="true">{{ trans('latraining.suggest_name') }}</th>
                    <th data-width="15%"
                        data-field="subject_name">{{ trans('latraining.suggest_unit') }}</th>
                    <th data-width="15%"
                        data-field="training_program_name">{{ trans('latraining.created_at') }}</th>
                    <th data-field="time" data-align="center" data-width="18%">{{ trans('latraining.created_by') }}</th>
                    <th data-align="center" data-width="10%" data-field="quizs">{{ trans('latraining.quiz') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-add-new" tabindex="-1" role="dialog" aria-labelledby="modal-add-new" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.quiz_educate_plan_suggest.save') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-import-training-program-learned">{{ trans('latraining.suggest_addnew') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="name">{{ trans('latraining.suggest_name') }}</label><span style="color:red"> * </span>
                            </div>
                            <div class="col-md-9">
                                <input name="name" id="name" type="text" class="form-control" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn">{{ trans('labutton.add_new') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz_educate_plan_suggest.getdata') }}',
            remove_url: '{{ route('module.quiz_educate_plan_suggest.remove') }}'
        });

        $( document ).ready(function() {
            $('.add_new').on('click', function () {
                $('#modal-add-new').modal();
            });

        });
    </script>
@endsection
