<button class="btn float-left" id="btnFilter"><i class="fas fa-filter"></i> {{ trans('labutton.filter') }}</button>
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
                            <input type="text" name="search" class="form-control" placeholder="{{ trans('backend.enter_question') }}">
                            <select name="type" class="form-control select2" data-placeholder="-- {{ trans('lasurvey.question_type') }} --">
                                <option value=""></option>
                                <option value="multiple-choise">{{trans("backend.multiple_choice")}}</option>
                                <option value="essay">{{trans("backend.essay")}}</option>
                                <option value="matching">{{trans("backend.matching_sentences")}}</option>
                                {{--  <option value="fill_in">{{ trans("backend.fill_in") }}</option>  --}}
                                <option value="fill_in_correct">{{ trans('latraining.fill_correct_answer') }}</option>
                                <option value="select_word_correct">{{ trans('latraining.choose_missing_word') }}</option>
                                <option value="drag_drop_marker">{{ trans('latraining.drag_marker') }}</option>
                                <option value="drag_drop_image">{{ trans('latraining.drag_image') }}</option>
                                <option value="drag_drop_document">{{ trans('latraining.drag_text') }}</option>
                            </select>
                            <select name="difficulty" class="form-control select2" data-placeholder="-- Mức độ --">
                                <option value=""></option>
                                <option value="D">Dễ</option>
                                <option value="TB">Trung bình</option>
                                <option value="K">Khó</option>
                            </select>
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
