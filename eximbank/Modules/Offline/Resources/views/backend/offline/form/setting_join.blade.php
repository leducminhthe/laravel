@if(isset($errors))
    @foreach($errors as $error)
        <div class="alert alert-danger">{!! $error !!}</div>
    @endforeach
@endif
<div class="row">
    <div class="col-md-9">
        <form method="post" action="{{ route('module.offline.save_setting_join', ['id' => $model->id]) }}" class="form-ajax" id="form-setting-join" data-success="submit_success_setting_join">
            <div class="form-group row">
                <div class="col-sm-4 control-label"></div>
                <div class="col-md-8">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" checked>
                        <label class="form-check-label" for="inlineRadio1"> {{ trans('laprofile.title') }}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="2">
                        <label class="form-check-label" for="inlineRadio2"> {{ trans('lacategory.title_level') }}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio4" value="4">
                        <label class="form-check-label" for="inlineRadio4">Import</label>
                    </div>
                </div>
            </div>
            <div class="form-group row wrapped_choose_1">
                <div class="col-sm-4 control-label">
                    <label class="name_type">
                        {{ trans('laprofile.title') }} <span class="text-danger">*</span>
                    </label>
                </div>
                <div class="col-md-8">
                    <div id="form-title">
                        <input type="hidden" name="title" class="form-control" value="">
                        <select id="title" class="load-title form-control" multiple data-placeholder="-- {{ trans('laprofile.title') }} --">
                        </select>
                        <input type="checkbox" id="checkbox-title"> {{ trans('latraining.select_all') }}
                        <input type="hidden" name="check_all_title" value="0">
                        <div class="noty_choose_all_title"></div>
                    </div>

                    <div id="form-title-rank">
                        <input type="hidden" name="title_rank" class="form-control" value="">
                        <select id="title_rank" class="load-title-rank form-control" multiple data-placeholder="-- {{ trans('lacategory.title_level') }} --">
                        </select>
                        <input type="checkbox" id="checkbox-title-rank"> {{ trans('latraining.select_all') }}
                        <input type="hidden" name="check_all_title_rank" value="0">
                        <div class="noty_choose_all_title_rank"></div>
                    </div>
                </div>
            </div>

            {{-- IMPORT --}}
            <div class="wrapped_choose_3">
                <div class="form-group row">
                    <div class="col-sm-4 control-label">
                    </div>
                    <div class="col-md-8">
                        <div class="btn-group">
                            <a class="btn" href="{{ download_template('mau_import_ghi_danh_tu_dong.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                            <button type="button" class="btn" id="model-import">
                                <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wrapped_setting_jon">
                <div class="form-group row">
                    <div class="col-sm-4 control-label">
                        <label for="date_register">{{ trans('laprofile.date_title_appointment') }} >= </label>
                    </div>
                    <div class="col-md-8">
                        <input name="date_register" id="date_register" value="" class="form-control is-number" placeholder="Nhập số ngày" />
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4 control-label">
                        <label for="date_register_join_company">{{ trans('laprofile.day_work') }} >=</label>
                    </div>
                    <div class="col-md-8">
                        <input name="date_register_join_company" id="date_register_join_company" value="" class="form-control is-number" placeholder="Nhập số ngày"/>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4 control-label"></div>
                    <div class="col-md-8">
                        @if($model->lock_course == 0)
                            <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('labutton.add_new') }}</button>
                            <button type="button" class="btn run_cron" onclick="runSettingJoin()"><i class="far fa-play-circle"></i> &nbsp;Chạy xử lý ghi danh</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4 control-label"></div>
                <div class="col-md-8">
                    <span class="text-danger">Lưu ý: Thời gian ghi danh tự động sẽ được xử lý sau 20h mỗi ngày</span>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="text-right">
            @if($model->lock_course == 0)
            <button id="delete-setting-join" class="btn"><i class="fa fa-trash"></i> {{trans('labutton.delete')}}</button>
            @endif
        </div>
        <p></p>
        <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table-setting-join">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="title_or_title_rank">{{ trans('latraining.title') .'/'. trans('lacategory.title_level') }}</th>
                    <th data-field="date_register" data-align="center" data-width="5%">
                        {{ trans('latraining.num_date') }} <br>
                        ({{ trans('laprofile.date_title_appointment') }})
                    </th>
                    <th data-field="date_register_join_company" data-align="center" data-width="5%">
                        {{ trans('latraining.num_date') }} <br>
                        ({{ trans('laprofile.day_work') }})
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modal-import" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <form action="{{ route('module.offline.setting_join.import', ['id' => $model->id]) }}" method="post" class="form-ajax">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_import">Import ghi danh tự động</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    function date_register_formatter(value, row, index) {
        return '<input class="change-date-register form-control is-number" value="'+ value +'" data-setting_join="'+row.id+'" />';
    }

    var table_setting_join = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.offline.get_setting_join', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.offline.remove_setting_join', ['id' => $model->id]) }}',
        detete_button: '#delete-setting-join',
        table: '#table-setting-join'
    });

    $('#form-title-rank').hide();
    $('.wrapped_choose_3').hide();

    $('#inlineRadio1').on('click', function(){
        $('#form-title').show();
        $('#form-title-rank').hide();
        $('.wrapped_choose_1').show();
        $('.wrapped_choose_3').hide();
        $('.wrapped_setting_jon').show();

        $("#checkbox-title-rank").prop("checked", false);
        $("#title_rank > option").prop("selected", "");
        $("#title_rank").trigger("change");
        $('input[name=title_rank]').val('');

        $('.name_type').html("{{ trans('laprofile.title') }} <span class='text-danger'>*</span>");
    });

    $('#inlineRadio2').on('click', function(){
        $('#form-title').hide();
        $('#form-title-rank').show();
        $('.wrapped_choose_1').show();
        $('.wrapped_choose_3').hide();
        $('.wrapped_setting_jon').show();

        $("#checkbox-title").prop("checked", false);
        $("#title > option").prop("selected", "");
        $("#title").trigger("change");
        $('input[name=title]').val('');

        $('.name_type').html("{{ trans('lacategory.title_level') }} <span class='text-danger'>*</span>")
    });

    $('#inlineRadio4').on('click', function(){
        $('.wrapped_choose_1').hide();
        $('.wrapped_choose_3').show();
        $('.wrapped_setting_jon').hide();
    });

    $('#title_rank').on('change', function () {
        var title_rank = $('#title_rank option:selected').map(function(){return $(this).val();}).get();
        $('input[name=title_rank]').val(title_rank);
        $('.noty_choose_all_title').html('')
        $('input[name=check_all_title]').val(0);
    })

    $('#title').on('change', function () {
        var title = $("#title option:selected").map(function(){return $(this).val();}).get();
        $('input[name=title]').val(title);
        $('.noty_choose_all_title_rank').html('')
        $('input[name=check_all_title_rank]').val(0);
    });

    $("#checkbox-title").click(function(){
        if($("#checkbox-title").is(':checked') ){
            $('input[name=check_all_title]').val(1);
            $('.noty_choose_all_title').html('<span class="text-danger">Chọn chức danh loại bỏ</span>')
        } else{
            $('input[name=check_all_title]').val(0);
            $('.noty_choose_all_title').html('')
        }
        $("#title").html("");
        $('input[name=title]').val('');
    });

    $("#checkbox-title-rank").click(function(){
        if($("#checkbox-title-rank").is(':checked') ){
            $('input[name=check_all_title_rank]').val(1);
            $('.noty_choose_all_title_rank').html('<span class="text-danger">Chọn cấp bậc chức danh loại bỏ</span>')
        }else{
            $('input[name=check_all_title_rank]').val(0);
            $('.noty_choose_all_title_rank').html('')
        }
        $("#title_rank").html("");
        $('input[name=title_rank]').val('');
    });

    function submit_success_setting_join(form) {
        $("#form-setting-join #title_rank").val(null).trigger('change');
        $("#form-setting-join #title").val(null).trigger('change');
        $("#form-setting-join #date_register").val(null);
        $("#form-setting-join #date_register_join_company").val(null);
        $("#form-setting-join #course_complete").val(null).trigger('change');

        table_setting_join.refresh();
    }

    $('#table-setting-join').on('change', '.change-date-register', function(){
        var setting_join = $(this).data('setting_join');
        var date_register = $(this).val();

        $.ajax({
            type: 'POST',
            url: '{{ route('module.offline.setting_join.change_date_register', ['id' => $model->id]) }}',
            dataType: 'json',
            data: {
                setting_join : setting_join,
                date_register: date_register,
            }
        }).done(function(data) {
            if (data.status !== "success") {
                show_message('Không thể lưu số ngày', 'error');
                return false;
            }

            table_setting_join.refresh();
            return false;
        }).fail(function(data) {
            return false;
        });
    })

    $('#model-import').on('click', function () {
        $('#modal-import').modal();
    });

    function runSettingJoin() {
        let item = $('.run_cron');
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> {{ trans("laother.please_wait") }}');
        $.ajax({
            type: 'POST',
            url: '{{ route('module.offline.run_cron_setting_join', ['id' => $model->id]) }}',
            dataType: 'json',
            data: {}
        }).done(function(data) {
            item.html(oldtext);
            show_message(data.message, data.status);
            return false;
        }).fail(function(data) {
            return false;
        });
    }
</script>
