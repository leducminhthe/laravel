<div class="row">
    <div class="col-md-8"></div>
    <div class="col-md-4 text-right act-btns">
        <div class="pull-right">
            <div class="btn-group">
                <button type="button" onclick="saveScoreRewardPoint(event)" class="btn save">{{ trans('labutton.save') }}</button>
            </div>
        </div>
    </div>
</div>
<br>
<table class="tDefault table table-hover bootstrap-table" id="table_reward_point">
    <thead>
        <tr>
            <th data-align="center" data-width="3%" data-formatter="stt_formatter">#</th>
            <th data-field="name">{{ trans('latraining.content') }}</th>
            <th data-field="status" data-formatter="status_formatter" data-align="center">{{ trans('latraining.status') }}</th>
            <th data-field="default_value" data-formatter="value_formatter" data-align="center">{{ trans('latraining.score') }}</th>
            <th data-field="setting_updated_at2" data-align="center">{{ trans('latraining.date_update') }}</th>
        </tr>
    </thead>
</table>
<script>
    function stt_formatter(value, row, index) {
        return (index + 1);
    }

    function status_formatter(value, row, index) {
        var html = `<select onchange="statusPromotion(`+ row.id +`,`+ (row.pvalue && row.pvalue > 0 ? row.pvalue : row.default_value) +`)" name="promotion_status[]" class="form-control promotion-status" id="status_`+ row.id +`">
                        <option value="0" `+ (row.pvalue ? 'selected' : '') +`>{{ trans('latraining.disable') }}</option>
                        <option value="1" `+ (row.pvalue ? 'selected' : '') +`>{{ trans('latraining.enable') }}</option>
                    </select>`
        return html;
    }

    function value_formatter(value, row, index) {
        var html = `<input type="hidden" class="ikey_`+ row.id +` form-control text-center" name="ikey[]" value="`+ row.ikey +`">
                    <input type="hidden" class="userpoint_id_`+ row.id +` form-control text-center" name="userpoint_id[]" value="`+ (row.setting_id ? row.setting_id : '') +`">
                    <input type="number" `+ (row.pvalue && row.pvalue > 0 ? '' : 'readonly') +` class="value_`+ row.id +` form-control text-center" value="`+ (row.pvalue && row.pvalue > 0 ? row.pvalue : '') +`" name="userpoint_others[]" placeholder="{{ trans("latraining.score") }}">`
        return html;
    }

    var table_reward_point = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.libraries.reward_point.get_data', ['id' => $model->id, 'type' => $type]) }}',
        table: '#table_reward_point',
    });

    function statusPromotion(id, value) {  
        var status = $('#status_'+ id).val();
        if (status == 0) {
            $('.value_'+id).attr("readonly", true)
            $('.value_'+id).val('');
        } else {
            $('.value_'+id).attr("readonly", false)
            $('.value_'+id).val(value);
        }
    }

    function saveScoreRewardPoint(event) {
        let item = $('.save');
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.processing") }}');
        $('.save').attr('disabled',true);

        var promotion_status = $.map($('.promotion-status'), function(el) { return el.value; });
        var userpoint_others = $.map($('input[name="userpoint_others[]"]'), function(el) { return el.value; });
        var userpoint_id = $.map($('input[name="userpoint_id[]"]'), function(el) { return el.value; });
        var ikey = $.map($('input[name="ikey[]"]'), function(el) { return el.value; });
        event.preventDefault();
        $.ajax({
            url: "{{ route('module.libraries.reward_point.save', ['id' => $model->id, 'type' => $type]) }}",
            type: 'post',
            data: {
                'promotion_status': promotion_status,
                'userpoint_others': userpoint_others,
                'userpoint_id': userpoint_id,
                'ikey': ikey,
            }
        }).done(function(data) {
            item.html(oldtext);
            $('.save').attr('disabled',false);
            if (data && data.status == 'success') {
                $(table_reward_point.table).bootstrapTable('refresh');
            } else {
                show_message(data.message, data.status);
            }
            return false;
        }).fail(function(data) {
            show_message('{{ trans('laother.data_error') }}', 'error');
            return false;
        });
    }
</script>