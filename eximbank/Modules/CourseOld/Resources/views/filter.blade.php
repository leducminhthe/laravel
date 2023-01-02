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
                            <input type="text" name="search_user" value="" class="form-control w-100" placeholder="{{ trans('backend.enter_code_name') }} {{ trans('backend.user')}}">
                            <input type="text" name="search_unit" value="" class="form-control w-100" placeholder="{{ trans('backend.filter_unit') }} ">
                            <input type="text" name="search_course" value="" class="form-control w-100" placeholder="{{ trans('backend.enter_code_name') }} {{trans('backend.course')}}">
                            <input name="start_date" type="text" class="form-control w-100 datetimepicker" placeholder="{{ trans('latraining.start_date') }}" autocomplete="off">
                            <input name="end_date" type="text" class="form-control w-100 datetimepicker" placeholder="{{ trans('latraining.end_date') }}" autocomplete="off">
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
