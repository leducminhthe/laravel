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
                            <select class="form-control w-100 select2" name="month">
                                <option value="">{{trans('lasuggest_plan.filter_month')}}</option>
                                @for ($i = 1; $i <=12; $i++)
                                    <option value="{{$i}}">{{ trans('lasuggest_plan.month_i', ['i' => $i]) }}</option>
                                @endfor
                            </select>
                            <select class="form-control select2 w-100" allowClear="false" name="year">
                                <option value="">{{trans('lasuggest_plan.filter_year')}}</option>
                                @for ($i = 2019; $i <= date('Y'); $i++)
                                    <option value="{{$i}}">{{ trans('lasuggest_plan.year_i', ['i' => $i]) }}</option>
                                @endfor
                            </select>
                            <select class="form-control select2" name="unit">
                                <option value="">{{trans('lasuggest_plan.filter_unit')}}</option>
                                @foreach($unit as $item)
                                    <option value="{{$item->code}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            <select class="form-control w-100 select2" name="status">
                                <option value="">{{trans('lasuggest_plan.filter_status')}}</option>
                                <option value="1">{{trans('lasuggest_plan.pending')}}</option>
                                <option value="2">{{trans('lasuggest_plan.approved')}}</option>
                                <option value="3">{{trans('lasuggest_plan.deny')}}</option>
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
