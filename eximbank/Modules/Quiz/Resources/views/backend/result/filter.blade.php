<button class="btn" id="btnFilter"><i class="fas fa-filter"></i> {{ trans('labutton.filter') }}</button>
<div class="modal left fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="myModalFilter" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" enctype="multipart/form-data" id="form-search">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <b>{{ trans('labutton.search') }}</b>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- @for($i = 1; $i <= 5; $i++)
                                <select name="unit_id" id="unit-{{ $i }}"
                                    class="form-control load-unit"
                                    data-placeholder="-- {{ trans('lamenu.unit_level',["i"=>$i]) }} --"
                                    data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0">
                                </select>
                            @endfor --}}
                            <div class="mb-2">
                                @include('backend.form_choose_unit')
                            </div>
                            <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('latraining.title') }} --"></select>
                            <select name="area" id="area" class="form-control load-area" data-placeholder="-- {{ trans('lacategory.area') }} --"></select>
                            <select name="status" class="form-control select2" data-placeholder="-- {{ trans('latraining.status') }} --">
                                <option value=""></option>
                                <option value="0">{{ trans('backend.inactivity') }}</option>
                                <option value="1">{{ trans('backend.doing') }}</option>
                                <option value="2">{{ trans('backend.probationary') }}</option>
                                <option value="3">{{ trans('backend.pause') }}</option>
                            </select>
                            <select name="type" class="form-control select2" data-placeholder="-- {{ trans('backend.examinee_type') }} --">
                                <option value=""></option>
                                <option value="1">{{trans('backend.internal_contestant')}}</option>
                                <option value="2">{{trans('backend.examinee_outside')}}</option>
                            </select>
                            <select name="part" class="form-control select2" data-placeholder="-- {{trans('backend.exams')}} --">
                                <option value=""></option>
                                @foreach ($quiz_part as $part)
                                    <option value="{{ $part->id }}" >{{ $part->name }}</option>
                                @endforeach
                            </select>
                            <select name="result_quiz" class="form-control select2" data-placeholder="-- {{ trans('backend.result') }} --">
                                <option value=""></option>
                                <option value="1"> {{trans('backend.submitted')}}</option>
                                <option value="2"> {{trans('backend.not_yet_submitted')}}</option>
                                <option value="3"> {{trans('backend.achieved')}}</option>
                                <option value="4"> {{trans('backend.not_achieved')}}</option>
                            </select>
                            <input type="text" name="search" class="form-control w-100" placeholder="{{ trans('latraining.enter_code_name_user') }}">
                            <div class="">
                                <button id="btnsearch" class="btn">
                                    <i class="fa fa-search"></i>&nbsp;{{ trans('labutton.search') }} 
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#btnFilter').on('click', function () {
        $('#modalFilter').modal();
    });
</script>