@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.learning_manager'),
                'url' => ''
            ],
            [
                'name' => trans('backend.trainingroadmap'). ': '. $title->name,
                'url' => route('module.trainingroadmap.detail', ['id' => $title_id])
            ],
            [
                'name' => $page_title,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main">
    <form method="post" action="{{ route('module.trainingroadmap.detail.save',['id' => $title_id] ) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['training-roadmap-create', 'training-roadmap-edit'])
                    <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.trainingroadmap.detail',['id' => $title_id] ) }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
        </div>

        <div class="clear"></div>

        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('latraining.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="training_program_id">{{trans('latraining.training_program')}}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="training_program_id" id="training_program_id" class="form-control select2" data-placeholder="-- {{ trans('app.training_program') }} --" required>
                                        <option value=""></option>
                                        @if(isset($training_program))
                                            <option value="{{ $training_program->id }}" selected> {{ $training_program->name }} </option>
                                        @endif
                                        @foreach ($trainingProgram as $item)
                                            <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="subject_id">{{trans('backend.subject')}}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="subject_id" id="subject_id" class="form-control load-subject" {{--data-level-subject="{{ $model->level_subject_id }}"--}} data-placeholder="-- {{ trans('backend.subject') }} --" required>
                                        @if(isset($subject))
                                            <option value="{{ $subject->id }}" selected> {{ $subject->name }} </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="name">{{trans('backend.form')}}</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="training_form" id="training_form" class="form-control select2" data-placeholder="-- {{trans('latraining.choose_form')}} --" >
                                        <option value=""></option>
                                        <option value="1" {{ $model->training_form == 1 ? 'selected' : '' }}>{{trans('lasuggest_plan.online')}}</option>
                                        <option value="2" {{ $model->training_form == 2 ? 'selected' : '' }}>{{trans('latraining.offline')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="name">{{ trans('laprofile.effective_date') }} ({{trans('latraining.date')}})</label>
                                </div>
                                <div class="col-md-1">
                                    <input name="completion_time" type="text" class="form-control is-number" value="{{ $model->completion_time }}">
                                </div>
                                <span style="color: #737373">({{trans('laother.calculated_from_date_finish')}})</span>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="name">{{trans('lasetting.order')}} </label>
                                </div>
                                <div class="col-md-1">
                                    <input name="order" type="text" class="form-control is-number"  value="{{$model->order}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="name">{{trans('latraining.description')}}</label>
                                </div>
                                <div class="col-md-6">
                                    <textarea name="content" type="text" class="form-control" rows="5" value="">{{ $model->content }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $('#training_program_id').on('select2:select', function (e) { 
        var training_program_id = $('#training_program_id option:selected').val();
        $("#subject_id").empty();
        $("#subject_id").data('training-program', training_program_id);
        $('#subject_id').trigger('change');
    });
    
    $('#subject_id').on('select2:select', function (e) { 
        var subjectId = $(this).val();
        $.ajax({
            url: '{{ route('module.trainingroadmap.detail.data_training_program',['id' => $title_id]) }}',
            type: 'post',
            data: {
                subjectId: subjectId,
            },
        }).done(function(data) {
            flag = 1;
            $("#training_program_id").val(data.trainingProgramId).change();
            return false;
        }).fail(function(data) {
            show_message('{{ trans('laother.data_error') }}', 'error');
            return false;
        });
    });
</script>
@stop
