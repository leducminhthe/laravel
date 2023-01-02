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
                                <select name="unit_id" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- {{ trans('lamenu.unit_level',["i"=>$i]) }} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                            @endfor --}}
                            <div class="mb-2">
                                @include('backend.form_choose_unit')
                            </div>
                            <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('latraining.title') }} --"></select>
                            <input type="text" name="search" value="" class="form-control w-100" placeholder="{{ trans('latraining.enter_code_employee_name_exam') }}">
                            <input type="text" name="start_date" value="" class="form-control w-100 datetimepicker" placeholder="{{ trans('latraining.start_date') }}">
                            <input type="text" name="end_date" value="" class="form-control w-100 datetimepicker" placeholder="{{ trans('latraining.end_date') }}">
                            <select name="result" id="" class="select2 form-control" data-placeholder="-- {{ trans('latraining.result') }} --">
                                <option value=""></option>
                                <option value="Không đạt">{{ trans('latraining.not_achieved') }}</option>
                                <option value="Đạt">{{ trans('latraining.passed') }}</option>
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
