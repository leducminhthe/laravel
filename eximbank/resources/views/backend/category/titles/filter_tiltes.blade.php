<button class="btn" id="btnFilter"><i class="fas fa-filter"></i> {{ trans('labutton.filter') }}</button>
<div class="modal left fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="myModalFilter" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form   role="form" enctype="multipart/form-data" id="form-search">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <b>{{ trans('labutton.search') }}</b>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- @for($i = 1; $i <= 5; $i++)
                                <select name="unit_id" id="unit-{{ $i }}" class="form-control load-unit unit_search" data-placeholder="-- {{ trans('lacategory.unit_level', ['i' => $i]) }} --" data-level="{{ $i }}" data-loadchild="unit-{{ ($i+1) }}">
                                </select>
                            @endfor --}}
                            <div class="mb-2">
                                @include('backend.form_choose_unit')
                            </div>
                            <select name="group" id="group" class="form-control select2" data-placeholder="--{{ trans('lacategory.title_level') }}--">
                                <option value=""></option>
                                @foreach ($title_ranks as $title_rank)
                                    <option value="{{ $title_rank->id }}"> {{ $title_rank->name }} </option>
                                @endforeach
                            </select>
                            <input type="text" name="search" class="form-control w-100" placeholder="{{ trans('lacategory.enter_code_name') }}">
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
    $('#btnsearch').on('click', function() {
        var latest_value = $(".unit_search option:selected:last").val();
        var group = $('#group').val();
        var search = $('input[name=search]').val();
    })
</script>
