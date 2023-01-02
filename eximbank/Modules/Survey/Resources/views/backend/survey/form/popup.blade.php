    <div class="row">
        <div class="col-md-9">
        <form method="post" action="{{ route('module.survey.save_popup', ['id' => $model->id]) }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data" data-success="submit_success_popup">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Số lần hiện thông báo <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                   <input type="text" name="num_notify" value="{{ @$model->num_notify }}" class="form-control is-number" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 control-label">{{trans('lasurvey.time')}} <span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <div>
                        <input name="start_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('lasurvey.start')}}" autocomplete="off" value="">
                        <select name="start_hour" id="start_hour" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0 ; $i <= 23 ; $i++)
                                @php $ii = $i < 10 ? '0'.$i : $i @endphp
                                <option value="{{$ii}}">{{$ii}}</option>
                            @endfor
                        </select>
                        giờ
                        <select name="start_min" id="start_min" class="form-control d-inline-block  w-25 date-custom">
                            @for($i = 0; $i <= 59; $i += 1)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}">{{$ii}}</option>
                            @endfor
                        </select>
                        phút
                    </div>
                    <div>
                        <input name="end_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('lasurvey.over')}}" autocomplete="off" value="">
                        <select name="end_hour" id="end_hour" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0 ; $i <= 23 ; $i++)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}">{{$ii}}</option>
                            @endfor
                        </select>
                        giờ
                        <select name="end_min" id="end_min" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0; $i <= 59; $i += 1)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}">{{$ii}}</option>
                            @endfor
                        </select>
                        phút
                    </div>
                </div>
            </div>
            @canany(['survey-create', 'survey-edit'])
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        <button type="submit" class="btn"><i class="fa fa-plus-circle"></i> {{trans('labutton.add_new')}}</button>
                    </div>
                </div>
            @endcanany
        </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @canany(['survey-create', 'survey-edit'])
            <div class="text-right">
                <button id="delete-popup" class="btn"><i class="fa fa-trash"></i> {{trans('labutton.delete')}}</button>
            </div>
            @endcanany
            <p></p>
            <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table-popup">
                <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="date" data-align="center">{{trans('lasurvey.time')}}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

<script type="text/javascript">
    var table_popup = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.survey.get_popup', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.survey.remove_popup', ['id' => $model->id]) }}',
        table: '#table-popup',
        detete_button: '#delete-popup',
    });

    function submit_success_popup(form) {
        $('input[name=start_date]').val('');
        $('input[name=end_date]').val('');

        $("select[id=start_hour]").val('00').trigger('change');
        $("select[id=start_min]").val('00').trigger('change');
        $("select[id=end_hour]").val('00').trigger('change');
        $("select[id=end_min]").val('00').trigger('change');

        table_popup.refresh();
    }
</script>