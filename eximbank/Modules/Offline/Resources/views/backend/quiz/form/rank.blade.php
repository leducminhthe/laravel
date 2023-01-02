<div role="main">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <label class="col-sm-3 control-label">{{ trans("latraining.classification") }} <span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="rank" value="">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 control-label">{{ trans('latraining.score') }} <span class="text-danger">*</span></label>

                <div class="col-sm-2">
                    <input type="text" class="form-control is-number" name="score_min" value="">
                </div>
                {{ trans("latraining.to") }}
                <div class="col-sm-2">
                    <input type="text" class="form-control is-number" name="score_max" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3"></div>
                <div class="col-sm-3">
                    <button type="button" class="btn save-rank"><i class="fa fa-plus-circle"></i> {{ trans("labutton.add_new") }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="tDefault table table-hover text-nowrap" id="table-rank">
                <thead>
                    <tr>
                        <th data-field="rank" data-align="center">{{ trans("latraining.classification") }}</th>
                        <th data-field="score_min" data-align="center">{{ trans("latraining.from_score") }}</th>
                        <th data-field="score_max" data-align="center">{{ trans("latraining.to_score") }}</th>
                        <th data-field="action" data-formatter="action_rank_formatter" data-align="center" data-width="5%">{{trans('latraining.action')}}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    function action_rank_formatter(value, row, index) {
        return '<a href="javascript:void(0)" class="remove-item" data-id="'+ row.id +'"><i class="fa fa-trash text-danger"></i></a>';
    }

    var table2 = new LoadBootstrapTable({
        url: '{{ route('module.quiz.edit.getrank', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.quiz.edit.removerank', ['id' => $model->id]) }}',
        table: '#table-rank'
    });

    $('.save-rank').on('click', function() {
        let button = $(this);
        let icon = button.find('i').attr('class');

        button.find('i').attr('class', 'fa fa-spinner fa-spin');
        button.prop("disabled", true);

        var rank = $('input[name=rank]').val();
        var score_min = $('input[name=score_min]').val();
        var score_max = $('input[name=score_max]').val();

        if (!score_min) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            show_message('Điểm tối thiếu không được trống', 'error');
            return false;
        }
        if (!score_max) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            show_message('Điểm tối đa không được trống', 'error');
            return false;
        }
        if (!rank) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            show_message('Xếp loại không được để trống', 'error');
            return false;
        }
        $.ajax({
            url: '{{ route('module.quiz.edit.saverank', ['id' => $model->id])}}',
            type: 'post',
            data: {
                rank: rank,
                score_min : score_min,
                score_max : score_max,
            },
        }).done(function(data) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            if(data.status == 'error'){
                show_message(data.message, 'error');
                return false;
            }else{
                show_message('Thêm thành công', 'success');
                $(table2.table).bootstrapTable('refresh');

                $('input[name=rank]').val('');
                $('input[name=score_min]').val('');
                $('input[name=score_max]').val('');
                return false;
            }

        }).fail(function(data) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });
</script>
