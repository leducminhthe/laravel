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
                            <input type="text" name="search" value="" class="form-control" autocomplete="off" placeholder="{{ trans('latraining.enter_code_name_course') }}">
                            <select name="training_program_id" id="training_program" class="form-control select2 load-training-program" data-placeholder="{{ trans('latraining.training_program') }}">
                            </select>
                            <select name="level_subject_id" id="level_subject" class="form-control select2 load-level-subject" data-placeholder="{{ trans('latraining.type_subject') }}">
                            </select>
                            <select name="subject_id" id="subject" class="form-control select2 load-subject" data-training-program="" data-level-subject="" data-placeholder="{{ trans('latraining.subject') }}">
                            </select>
                            <input name="start_date" type="text" class="form-control datetimepicker" placeholder="{{ trans('latraining.start_date') }}" autocomplete="off">
                            <input name="end_date" type="text" class="form-control datetimepicker" placeholder="{{ trans('latraining.end_date') }}" autocomplete="off">
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
