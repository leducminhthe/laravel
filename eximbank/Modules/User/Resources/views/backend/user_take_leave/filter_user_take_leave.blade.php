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
                                <div class="">
                                    <select name="unit_id" id="unit-{{ $i }}" class="form-control load-unit unit_search" data-placeholder="-- {{ trans('lacategory.unit_level', ['i' => $i]) }} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                                </div>
                            @endfor --}}
                            <div class="mb-1">
                                @include('backend.form_choose_unit')
                            </div>
                            <div class="">
                                <select name="area" id="area" class="form-control load-area" data-placeholder="-- {{ trans('laprofile.area') }} --"></select>
                            </div>
                            <div class="">
                                <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('laprofile.title') }} --"></select>
                            </div>

                            <div class="">
                                <input type="text" name="search" class="form-control w-100" placeholder="{{ trans('laprofile.enter_code_name_user') }}">
                            </div>
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
