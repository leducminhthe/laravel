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
                            <select name="training_program_id" id="training_program" class="form-control select2 load-training-program w-100" data-placeholder="{{ trans('lacategory.training_program') }}">
                            </select>
                            <select name="level_subject_id" id="level_subject" data-training-program="" class="form-control select2 load-level-subject w-100" data-placeholder="{{ trans('lacategory.type_subject') }}">
                            </select>
                            <input type="text" name="search" value="" class="form-control w-100" placeholder="{{trans('lacategory.enter_code_name')}}">
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

    $('#training_program').on('change', function () {
        var training_program_id = $('#training_program option:selected').val();

        $("#level_subject").empty();
        $("#level_subject").data('training-program', training_program_id);
        $("#level_subject").trigger('change');
    });
</script>
