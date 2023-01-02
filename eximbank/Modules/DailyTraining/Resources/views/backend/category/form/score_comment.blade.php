<div class="row">
    <div class="col-md-8"></div>
    <div class="col-md-4 text-right act-btns">
        <div class="pull-right">
            <div class="btn-group">
            @can('daily-training-reawrd-point-create')
                <button style="cursor: pointer;" onclick="createScoreComment()" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
            @endcan
            @can('daily-training-reawrd-point-delete')
                <button class="btn" id="delete-score-comment"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
            @endcan
            </div>
        </div>
    </div>
</div>
<br>
<table class="tDefault table table-hover bootstrap-table" id="table_score_comment">
    <thead>
        <tr>
            <th data-field="check" data-checkbox="true" data-width="2%"></th>
            <th data-field="from">{{ trans('backend.from') }}</th>
            <th data-field="to">{{ trans('backend.to') }}</th>
            <th data-field="score">{{ trans('backend.score') }}</th>
            <th data-align="center" data-formatter="edit_formatter_score_comment">{{ trans('labutton.edit') }}</th>
        </tr>
    </thead>
</table>

<div class="modal fade" id="modal-score-comment" tabindex="-1" role="dialog" aria-labelledby="modal_score_comment" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" id="ajax-modal-score-comment" role="document">
        <form action="" method="post" class="form-ajax" id="form_save_score_comment" onsubmit="return false;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_score_comment"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="body_modal_score_comment">
                    <input type="hidden" name="id_score_comment" value="">
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.comments_from') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input name="from_score_comment" type="text" placeholder="{{ trans('backend.enter_quantity') }}" min="1" class="form-control is-number" value="" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.comments_to') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input name="to_score_comment" type="text" placeholder="{{ trans('backend.enter_quantity') }}" class="form-control is-number" value="" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.score') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input name="score_comment" type="text" placeholder="{{ trans('backend.enter_score') }}" class="form-control is-number" value="" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @canany(['daily-training-reawrd-point-create','daily-training-reawrd-point-edit'])
                        <button type="button" onclick="saveScoreComment(event)" class="btn save">{{ trans('labutton.save') }}</button>
                    @endcan
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    function edit_formatter_score_comment(value, row, index) {
        return '<a id="edit_score_comment_'+ row.id +'" style="cursor: pointer;" onclick="editScoreComment('+ row.id +')"><i class="uil uil-edit-alt"></i></a>' ;
    }
    var table_score_comment = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.daily_training.score_comment.getdata', ['category_id' => $category->id]) }}',
        remove_url: '{{ route('module.daily_training.score_comment.remove', ['category_id' => $category->id]) }}',
        table: '#table_score_comment',
        detete_button: '#delete-score-like',
    });

    function editScoreComment(id){
        let item = $('#edit_score_comment_'+id);
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
        $.ajax({
            url: "{{ route('module.daily_training.score_comment.edit', ['category_id' => $category->id]) }}",
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            item.html(oldtext);
            $('#modal_score_comment').html('{{ trans('labutton.edit') }}');
            $("input[name=id_score_comment]").val(data.id);
            $("input[name=from_score_comment]").val(data.from);
            $("input[name=to_score_comment]").val(data.to);
            $("input[name=score_comment]").val(data.score);
            $('#modal-score-comment').modal();
            return false;
        }).fail(function(data) {
            show_message('{{ trans('laother.data_error') }}', 'error');
            return false;
        });
    }

    function saveScoreComment(event) {
        let item = $('.save');
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
        $('.save').attr('disabled',true);

        var form = $('#form_save_score_comment');
        var from =  $("input[name=from_score_comment]").val();
        var id =  $("input[name=id_score_comment]").val();
        var to =  $("input[name=to_score_comment]").val();
        var score = $("input[name=score_comment]").val();
        event.preventDefault();
        $.ajax({
            url: "{{ route('module.daily_training.score_comment.save', ['category_id' => $category->id]) }}",
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
                $('#modal-score-comment').modal('hide');
                show_message(data.message, data.status);
                $(table_score_comment.table).bootstrapTable('refresh');
            } else {
                show_message(data.message, data.status);
            }
            return false;
        }).fail(function(data) {
            show_message('{{ trans('laother.data_error') }}', 'error');
            return false;
        });
    }

    function createScoreComment() {
        $("input[name=from_score_comment]").val('');
        $("input[name=id_score_comment]").val('');
        $("input[name=to_score_comment]").val('');
        $("input[name=score_comment]").val('');
        $('#modal_score_comment').html('{{ trans('labutton.add_new') }}');
        $('#modal-score-comment').modal();
    }
</script>
