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
                                <select name="unit_id" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- {{ trans('lacategory.unit_level', ['i' => $i]) }} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                            @endfor --}}
                            <div class="mb-2">
                                @include('backend.form_choose_unit')
                            </div>
                            <select name="unit_type" class="select2 form-control" id="" data-placeholder="--{{ trans('lacategory.choose_unit_type') }}--">
                                <option value=""></option>
                                <option value="1">Hội sở</option>
                                <option value="2">Đơn vị kinh doanh</option>
                            </select>
                            <select name="title_rank" class="select2 form-control" id="" data-placeholder="--{{ trans('lacategory.title_level') }}--">
                                <option value=""></option>
                                @foreach ($titles_rank as $title_rank)
                                    <option value="{{ $title_rank->id }}">{{ $title_rank->name }}</option>
                                @endforeach
                            </select>
                            <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('latraining.title') }} --"></select>
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
