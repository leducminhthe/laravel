@extends('layouts.app')

@section('page_title', trans('lamenu.kirkpatrick_model'))

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <h2 class="st_title">
                                        <a href="/">
                                            <i class="uil uil-apps"></i>
                                            <span>{{ trans('lamenu.home_page') }}</span>
                                        </a>
                                        <i class="uil uil-angle-right"></i>
                                        <span class="font-weight-bold">{{ trans('lamenu.kirkpatrick_model') }}</span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-search mb-2 row w-100" id="form-search">
                                    <div class="col-md-3 col-12 pr-0">
                                        <input type="text" name="search" class="form-control w-100 pr-0" placeholder="{{ trans('labutton.search') .' '. trans('app.course') }}" value="">
                                    </div>
                                    <div class="col-md-3 col-12 pr-0">
                                        <select name="status" class="form-control w-100">
                                            <option value="">{{ trans('laother.status') }}</option>
                                            <option value="0"> {{ trans('laother.not_rated_yet') }}</option>
                                            <option value="1"> {{ trans('laother.have_rated') }}</option>
                                            <option value="2"> {{ trans('laother.end') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input name="start_date" type="text" class="datepicker form-control search_start_date" placeholder="{{trans('laother.start_date')}}">
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <input name="end_date" type="text" class="datepicker form-control search_end_date" placeholder="{{trans('laother.end_date')}}">
                                    </div>
                                    <div class="col-md-1 col-12">
                                        <button class="btn btn-search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>

                                </form>
                            </div>
                        </div>
                        <br>
                        <div class="row" id="course">
                            <div class="col-md-12">
                                <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table-rating-level">
                                    <thead>
                                    <tr>
                                        <th data-field="rating_url" data-formatter="rating_url_formatter" data-align="center">{{ trans('latraining.evaluate') }}</th>
                                        <th data-field="rating_name">{{ trans('latraining.rating_name') }}</th>
                                        <th data-field="course_name" data-formatter="course_name_formatter">{{ trans('laother.course') }}</th>
                                        <th data-field="rating_time">{{ trans('latraining.time_rating') }}</th>
                                        <th data-field="object_rating">{{ trans('latraining.evaluation_object') }}</th>
                                        <th data-field="rating_status" data-align="center">{{ trans('laother.status') }}</th>
                                        @if($is_manager)
                                            <th data-field="colleague" data-formatter="add_colleague_formatter" data-align="center">{{ trans('laother.more_colleagues') }}</th>
                                        @endif
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="modal-notify-rating">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="element_data"
        data-url_rating_level = '{{ route('module.rating_level.getdata') }}'
    >
    </div>
    <script src="{{ mix('js/rating_level.js') }}" type="text/javascript"></script>
@stop
