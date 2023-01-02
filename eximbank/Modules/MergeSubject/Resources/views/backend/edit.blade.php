@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.learning_manager'),
                'url' => route('module.mergesubject.index')
            ],
            [
                'name' => $page_title. ': '. $subject_new->name,
                'url' =>  ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main">
    <form method="post" action="{{ route('module.mergesubject.update',['id'=>$id]) }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['mergesubject-create', 'mergesubject-edit'])
                        <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.mergesubject.index') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('latraining.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group row">
                                <div class="col-md-3">&nbsp;</div>
                                <div class="col-md-9">
                                    <input type="radio"  class="radio-inline" name="mergeOption" id="option1" value="1" {{$model->merge_option==1 || !$model->id ?'checked':'' }}  >
                                    <label for="option1">Số lượng chuyên đề cần hoàn thành</label>
                                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                    <input type="radio"  class="radio-inline" name="mergeOption" id="option2" value="2" {{$model->merge_option==2 ?'checked':'' }}>
                                    <label for="option2">Chọn cụ thể từng chuyên đề cần hoàn thành</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="mergeOption-1" {{$model->merge_option==2  ?'hidden' : ''}}>
                        <div class="col-md-10">
                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>Số chuyên đề cần hoàn thành</label><span style="color:red"> * </span>
                                </div>
                                <div class="col-md-8">
                                    <input name="subject_old_complete" type="text" class="form-control" value="{{$model->subject_old_complete}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>Chọn chuyên đề cần gộp</label><span style="color:red"> * </span>
                                </div>
                                <div class="col-md-8">
                                    <select name="subject_old[]" class="form-control select2" multiple>
                                        @foreach ($subjects as $item=>$value)
                                            @php
                                                $q = $value->id;
                                                $equal=array_filter($subject_old_rr, function($v ) use ($q){
                                                    return $v ==$q;
                                                })
                                            @endphp
                                            <option value="{{$value->id}}" {{$equal?'selected':''}}>{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>Chuyên đề mới</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="subject_new" id="subject_new" class="select2" data-placeholder="-- {{ trans('backend.subject') }} --">
                                        <option value="">-- {{ trans('backend.subject') }} --</option>
                                        @foreach ($subjects as $item=>$value)
                                            <option value="{{$value->id}}" {{$value->id==$model->subject_new?'selected':''}} >{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>{{ trans('latraining.note') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="note">{{$model->note}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="mergeOption-2" {{$model->merge_option==1 || !$model->id ?'hidden' : ''}} >
                        <div class="col-md-10">
                            <div id="wrap-category">
                                @foreach($subject_old_arr as $subject)
                                <div class="form-group row">
                                    <div class="col-sm-4 control-label">
                                        <label>Chọn chuyên đề cần gộp</label><span style="color:red"> * </span>
                                    </div>
                                    <div class="col-md-6">

                                        <select class="load-subject" name="subject_old_2[]" data-placeholder="-- {{ trans('backend.subject') }} --">
                                            @php
                                                $sub = $subject_list($subject[0]);
                                            @endphp
                                            <option value="{{$sub->id}}">{{$sub->name}}</option>
                                        </select>

                                    </div>
                                    <div class="col-md-2">
                                        <label >Hoàn thành <input type="checkbox" {{$subject[1]==1?'checked':''}}  class="subject_old_complete_2" name="subject_old_complete_2[]" /></label>
                                        <input type="hidden" name="subject_old_complete_hidden[]" value="{{$subject[1]}}">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn add-oldSubject"><i class="glyphicon glyphicon-plus-sign"></i> Thêm chuyên đề cần gộp</button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4 control-label"><label>Chuyên đề mới</label> <span style="color:red"> * </span></div>
                                <div class="col-sm-6">
                                    <select name="subject_new_2" id="subject_new_2" class="load-subject" data-placeholder="-- {{ trans('backend.subject') }} --">
                                        <option value="{{$subject_new->id}}">{{$subject_new->name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">{{ trans('latraining.note') }}</div>
                                <div class="col-sm-6">
                                    <textarea class="form-control" name="note_2">{{$model->note}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
<template id="template">
    <div class="form-group row">
        <div class="col-sm-4 control-label">
            <label>Chọn chuyên đề cần gộp</label><span style="color:red"> * </span>
        </div>
        <div class="col-md-6">
            <select class="subject_old_2" name="subject_old_2[]" data-placeholder="-- {{ trans('backend.subject') }} --">
                @foreach ($subjects as $item=>$value)
                    <option value="{{$value->id}}">{{$value->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label >Hoàn thành <input type="checkbox" checked class="subject_old_complete_2" name="subject_old_complete_2[]" /></label>
            <input type="hidden" name="subject_old_complete_hidden[]" value="1">
        </div>
    </div>
</template>
<script type="text/javascript">
    $(document).ready(function() {
        $("input[name=mergeOption]").on("change", function () {
            var mergeOption = $(this).val();
            if (mergeOption == 1) {
                $("#mergeOption-1").attr('hidden', false);
                $("#mergeOption-2").attr('hidden', true);
            } else if (mergeOption == 2) {
                $("#mergeOption-1").attr('hidden', true);
                $("#mergeOption-2").attr('hidden', false);
            }
        });
        $(document).on('change','.subject_old_complete_2',function () {
            if($(this).is(':checked')){
                $(this).closest(".col-md-2").children("input[type=hidden]").val(1);
            }
            else{
                $(this).closest(".col-md-2").children("input[type=hidden]").val(0);
            }

        });
            // $('.subjectselect2').select2();


        $('.add-oldSubject').on('click', function () {
            var $content = document.getElementById('template').innerHTML;
            $('#wrap-category').append($content);
            $('.subject_old_2').select2({
                allowClear: true,
                dropdownAutoWidth: true,
                width: '100%',
                placeholder: function (params) {
                    return {
                        id: null,
                        text: params.placeholder,
                    }
                },
            }).val('').trigger('change');
        })
    });
</script>
@stop
