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
                            <select name="cate_id" id="cate_id" class="form-control select2" data-placeholder="{{ trans('lalibrary.category') }}">
                                <option value=""></option>
                                @foreach ($cates as $cate)
                                    <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="search" value="" class="form-control w-100" placeholder="{{trans('backend.titles')}}">
                            <input name="start_date" type="text" class="form-control w-100 datetimepicker" placeholder="{{trans('latraining.start_date')}}" autocomplete="off">
                            <input name="end_date" type="text" class="form-control w-100 datetimepicker" placeholder="{{trans('latraining.end_date')}}" autocomplete="off">
                            <select name="type" id="type" class="form-control select2" data-placeholder="{{ trans('latraining.type') }}">
                                <option value=""></option>
                                <option value="1">{{ trans('latraining.post') }}</option>
                                <option value="2">{{ trans('lamenu.video') }}</option>
                                <option value="3">{{ trans('latraining.picture') }}</option>
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
