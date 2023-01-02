@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.learning_manager'),
                'url' => route('module.splitsubject.index')
            ],
            [
                'name' => $page_title. ': '. trans('labutton.add_new'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main">
    <form method="post" action="{{ route('module.splitsubject.store') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['splitsubject-create', 'splitsubject-edit'])
                        <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.splitsubject.index') }}" class="btn"><i class="fa fa-times-circle"></i> {{ trans('labutton.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="tPanel">
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row"  >
                        <div class="col-md-10">
                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>{{ trans('latraining.subject_split_name') }}</label><span style="color:red"> * </span>
                                </div>
                                <div class="col-md-8">
                                    <select name="subject_new" id="subject_new" class="select2" data-placeholder="-- {{ trans('backend.subject') }} --">
                                        <option value="">{{ trans('backend.subject') }}</option>
                                        @foreach ($subjects as $item=>$value)
                                            <option value="{{$value->id}}" >{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>{{ trans('latraining.new_subject') }}</label><span style="color:red"> * </span>
                                </div>
                                <div class="col-md-8">
                                    <select name="subject_old[]" class="form-control load-subject" multiple>
                                        @foreach ($subjects as $item=>$value)
                                            <option value="{{$value->code}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>{{ trans('latraining.note') }}</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="note"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
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
