@extends('layouts.backend')

@section('page_title', trans('backend.detail_training_program'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training'),
                'url' => ''
            ],
            [
                'name' => trans('backend.training_plan'),
                'url' => route('module.training_plan')
            ],
            [
                'name' => trans('backend.detail_training_program'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        @if(isset($errors))

        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach

        @endif

        <div class="row">
            <div class="col-md-12 form-inline">
                <form class="form-inline form-search-user mb-3  w-100" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{trans('latraining.enter_code_name')}}">
                    <div class="w-24">
                        <select name="training_program_id" id="training_program_id" class="form-control load-training-program" data-placeholder="-- {{ trans('latraining.training_program') }} --"></select>
                    </div>
                    <div class="w-24">
                        <select name="level_subject_id" id="level_subject_id" class="form-control load-level-subject" data-training-program="" data-placeholder="-- {{trans('backend.levels')}} --"></select>
                    </div>
                    <div class="w-20">
                        <select name="course_type" id="course_type" class="form-control select2" data-placeholder="-- {{trans('backend.training_program_form')}} --">
                            <option value=""></option>
                            <option value="1"> {{ trans('backend.onlines') }}</option>
                            <option value="2"> {{ trans('latraining.offline') }}</option>
                            <option value="3"> {{ trans('backend.self_learning') }}</option>
                        </select>
                    </div>
                    <div class="w-20">
                        <button type="submit" class="btn"><i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }} </button>
                    </div>
                </form>
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn" href="{{ route('module.training_plan.detail.export_plan', ['id' => $plan_id]) }}"><i class="fa fa-download"></i>
                            {{ trans('labutton.export') }}</a>
                    </div>
                    @can('training-plan-detail-create')
                        <div class="btn-group">
                            <a class="btn" href="{{ route('module.training_plan.detail.export_template', ['id' => $plan_id]) }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                            <button class="btn" id="import-plan" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                            </button>
                        </div>
                    @endcan
                    <div class="btn-group">
                        @can('training-plan-detail-create')
                        <a href="{{ route('module.training_plan.detail.create', ['id' => $plan_id]) }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('training-plan-detail-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" id="table_detail_plan">
            <thead>
                <tr class="tbl-heading">
                    <th rowspan="2" data-field="state" data-checkbox="true"></th>
                    <th rowspan="2" data-field="code">{{ trans('lacourse.course_code') }}</th>
                    <th rowspan="2" data-field="subject_name" data-formatter="name_formatter">{{ trans('latraining.course') }}</th>
                    <th rowspan="2" data-field="program_name">{{ trans('latraining.training_program') }}</th>
                    <th rowspan="2" data-field="course_type">{{ trans('backend.form') }} <br> {{ trans('lamenu.training') }}</th>
                    <th rowspan="2" data-field="training_form">{{ trans('backend.training_form') }}</th>
                    <th rowspan="2" data-field="exis_training_CBNV">{{ trans('latraining.existing_training_needs') }}</th>
                    <th rowspan="2" data-field="recruit_training_CBNV">{{ trans('latraining.recruit_training_needs') }}</th>
                    <th rowspan="2" data-field="total_course">{{ trans('latraining.total_class') }}</th>
                    <th rowspan="2" data-field="periods">{{ trans('latraining.training_duration_class') }}</th>
                    <th colspan="4" >
                        {{ trans('latraining.training_organization_plan') }} 
                        <br> 
                        ({{ trans('latraining.unit_measure') }}: {{ trans('latraining.classroom') }})
                    </th>
                    <th colspan="{{ $count_type_costs }}">
                        {{ trans('latraining.training_cost') }} 
                        <br> 
                        ({{ trans('latraining.unit_measure') }}: VNĐ)
                    </th>
                    <th rowspan="2" data-field="total_type_cost">{{ trans('latraining.total_training_costs') }}</th>
                </tr>
                <tr class="tbl-heading">
                    <th data-field="quarter1"{{ trans('latraining.quarter1') }}</th>
                    <th data-field="quarter2">{{ trans('latraining.quarter2') }}</th>
                    <th data-field="quarter3">{{ trans('latraining.quarter3') }}</th>
                    <th data-field="quarter4">{{ trans('latraining.quarter4') }}</th>
                    @foreach ($type_costs as $key => $type_cost)
                        <th data-field="type_cost_{{ $type_cost->id }}">{{ $type_cost->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.training_plan.detail.import_plan', ['id' => $plan_id]) }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.IMPORT_TRAINING_PLAN') }}</h5>
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

    <script type="text/javascript">
        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.subject_name+'</a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_plan.detail.getdata', ['id' => $plan_id]) }}',
            remove_url: '{{ route('module.training_plan.detail.remove', ['id' => $plan_id]) }}'
        });
    </script>
@endsection
