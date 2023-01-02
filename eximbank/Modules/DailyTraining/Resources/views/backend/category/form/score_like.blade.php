<div class="row">
    <div class="col-md-8"></div>
    <div class="col-md-4 text-right act-btns">
        <div class="pull-right">
            <div class="btn-group">
                @can('daily-training-reawrd-point-create')
                    <button style="cursor: pointer;" onclick="createScoreLike()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                @endcan
                @can('daily-training-reawrd-point-delete')
                    <button class="btn" id="delete-score-like"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                @endcan
            </div>
        </div>
    </div>
</div>
<br>
<table class="tDefault table table-hover bootstrap-table" id="table_score_like">
    <thead>
        <tr>
            <th data-field="check" data-checkbox="true" data-width="2%"></th>
            <th data-field="from">{{ trans('backend.from') }}</th>
            <th data-field="to">{{ trans('backend.to') }}</th>
            <th data-field="score">{{ trans('backend.score') }}</th>
            <th data-align="center" data-formatter="edit_formatter_score_like">{{ trans('labutton.edit') }}</th>
        </tr>
    </thead>
</table>

<div class="modal fade" id="modal-score-like" tabindex="-1" role="dialog" aria-labelledby="modal_score_like" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" id="ajax-modal-score-like" role="document">
        <form action="" method="post" class="form-ajax" id="form_save_score_like" onsubmit="return false;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_score_like"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="body_modal_score_like">
                    <input type="hidden" name="id_score_like" value="">
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.likes_from') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input name="from_score_like" type="text" placeholder="{{ trans('backend.enter_quantity') }}" min="1" class="form-control is-number" value="" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.likes_to') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input name="to_score_like" type="text" placeholder="{{ trans('backend.enter_quantity') }}" class="form-control is-number" value="" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.score') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input name="score_like" type="text" placeholder="{{ trans('backend.enter_score') }}" class="form-control is-number" value="" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @canany(['daily-training-reawrd-point-create','daily-training-reawrd-point-edit'])
                        <button type="button" onclick="saveScoreLike(event)" class="btn save">{{ trans('labutton.save') }}</button>
                    @endcan
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    function edit_formatter_score_like(value, row, index) {
        return '<a id="edit_score_like_'+ row.id +'" style="cursor: pointer;" onclick="editScoreLike('+ row.id +')"><i class="uil uil-edit-alt"></i></a>' ;
    }
    var table_score_like = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.daily_training.score_like.getdata', ['category_id' => $category->id]) }}',
        remove_url: '{{ route('module.daily_training.score_like.remove', ['category_id' => $category->id]) }}',
        table: '#table_score_like',
        detete_button: '#delete-score-like',
    });

    function editScoreLike(id){
        let item = $('#edit_score_like_'+id);
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
        $.ajax({
            url: "{{ route('module.daily_training.score_like.edit', ['category_id' => $category->id]) }}",
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            item.html(oldtext);
            $('#modal_score_like').html('{{ trans('labutton.edit') }}');
            $("input[name=id_score_like]").val(data.id);
            $("input[name=from_score_like]").val(data.from);
            $("input[name=to_score_like]").val(data.to);
            $("input[name=score_like]").val(data.score);
            $('#modal-score-like').modal();
            return false;
        }).fail(function(data) {
            show_message('{{ trans('laother.data_error') }}', 'error');
            return false;
        });
    }

    function saveScoreLike(event) {
        let item = $('.save');
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
        $('.save').attr('disabled',true);

        var form = $('#form_save_score_like');
        var from =  $("input[name=from_score_like]").val();
        var id =  $("input[name=id_score_like]").val();
        var to =  $("input[name=to_score_like]").val();
        var score = $("input[name=score_like]").val();
        event.preventDefault();
        $.ajax({
            url: "{{ route('module.daily_training.score_like.save', ['category_id' => $category->id]) }}",
            type: 'post',
            data: {
                'from': from,
                'id': id,
                'to': to,
                'score' : score
            }
        }).done(function(data) {
            item.html(oldtext);
            $('.save').attr('disabled',false);
            if (data && data.status == 'success') {
                $('#modal-score-like').modal('hide');
                show_message(data.message, data.status);
                $(table_score_like.table).bootstrapTable('refresh');
            } else {
                show_message(data.message, data.status);
            }
            return false;
        }).fail(function(data) {
            show_message('{{ trans('laother.data_error') }}', 'error');
            return false;
        });
    }

    function createScoreLike() {
        $("input[name=from_score_like]").val('');
        $("input[name=id_score_like]").val('');
        $("input[name=to_score_like]").val('');
        $("input[name=score_like]").val('');
        $('#modal_score_like').html('{{ trans('labutton.add_new') }}');
        $('#modal-score-like').modal();
    }
</script>

