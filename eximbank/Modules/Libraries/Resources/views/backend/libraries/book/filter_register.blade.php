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
                            <input type="text" name="search" value="" class="form-control w-100" autocomplete="off" placeholder="{{trans('labutton.search')}}">
                            <input name="borrow_date" type="text" class="datetimepicker form-control w-100" placeholder="{{trans('backend.date_borrow')}}" autocomplete="off">
                            <input name="pay_date" type="text" class="datetimepicker form-control w-100" placeholder="{{trans('backend.pay_day')}}" autocomplete="off">
                            <select name="status" id="status" class="form-control select2" data-placeholder="{{trans('latraining.status')}}">
                                <option value=""></option>
                                <option value="1">{{trans('backend.get_book_yet')}}</option>
                                <option value="2">{{trans('backend.borrowing_book')}}</option>
                                <option value="3">{{trans('backend.book_back')}}</option>
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
