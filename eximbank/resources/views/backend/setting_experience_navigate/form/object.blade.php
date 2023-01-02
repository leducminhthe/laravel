<div class="row">
    <div class="col-12 mb-3">
        <p style="color: red">({{ trans('latraining.note') }}: {{ trans('laother.note_navigate') }})</p>
    </div>
    <div class="col-md-9">
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('backend.object_belong') }}</label>
            </div>
            <div class="col-md-6">
                <label class="radio-inline"><input type="radio" name="object" value="1" checked> {{ trans('lamenu.unit') }} </label>
                <label class="radio-inline"><input type="radio" name="object" value="2"> {{ trans('latraining.title') }} </label>
            </div>
        </div>
        <form method="post" action="{{ route('backend.experience_navigate.object', ['id' => $model->id]) }}" class="form-ajax" role="form" enctype="multipart/form-data" data-success="submit_success">
            <div id="object-unit">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label> {{ trans('lamenu.unit') }} </label>
                    </div>
                    <div class="col-md-9">
                        <select name="unit_id[]" multiple id="unit-1" class="form-control load-unit" data-placeholder="-- {{ trans('lamenu.unit') }} --" data-level="1"></select>
                    </div>
                </div>
            </div>
            <div id="object-title">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label> {{ trans('latraining.title') }} </label>
                    </div>
                    <div class="col-md-9">
                        <select id="title" class="form-control select2" multiple data-placeholder="-- {{ trans('latraining.title') }} --">
                            @foreach($titles as $title)
                                <option value="{{ $title->id }}">{{ $title->name }}</option>
                            @endforeach
                        </select>
                        <input type="checkbox" id="checkbox" >{{ trans('backend.select_all') }}
                    </div>
                    <input type="hidden" name="title_id" class="form-control title" value="">
                </div>
            </div>
            <div id="object-add">
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        <button type="submit" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-12" id="form-object">
        <div id="table-object">
            <div class="text-right">
                <button id="delete-item" class="btn"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
            </div>
            <p></p>
            <table class="tDefault table table-hover bootstrap-table text-nowrap">
                <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="unit_name"> {{ trans('lamenu.unit') }}</th>
                        <th data-field="title_name">{{ trans('latraining.title') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('backend.experience_navigate.get_object', ['id' => $model->id]) }}',
        remove_url: '{{ route('backend.experience_navigate.remove_object', ['id' => $model->id]) }}'
    });

    $('#title').on('change', function () {
        var title = $("#title option:selected").map(function(){return $(this).val();}).get();
        $('.title').val(title);
    });

    $("#checkbox").click(function(){
        if($("#checkbox").is(':checked') ){
            $("#title > option").prop("selected","selected");
            $("#title").trigger("change");

            var title = $("#title option:selected").map(function(){return $(this).val();}).get();
            $('.title').val(title);
        }else{
            $("#title > option").prop("selected", "");
            $("#title").trigger("change");
            $('.title').val('');
            $(table.table).bootstrapTable('refresh');
        }
    });

    function submit_success(form) {
        $("#title > option").prop("selected", "");
        $("#title").trigger("change");
        $('.title').val('');
        $("#checkbox").prop('checked', false);
        $("#object-unit select[name=unit_id\\[\\]]").val(null).trigger('change');
        table.refresh();
    }


    var object = $("input[name=object]").val();
    if (object == 1) {
        $("#object-add").show('slow');
        $("#object-unit").show('slow');
        $("#object-title").hide('slow');
        $("#table-object").show('slow');
    } else {
        $("#object-add").show('slow');
        $("#object-unit").hide('slow');
        $("#object-title").show('slow');
        $("#table-object").show('slow');
    }


    $("input[name=object]").on('change', function () {
    var object = $(this).val();
    if (object == 1) {
        $("#object-add").show('slow');
        $("#object-unit").show('slow');
        $("#object-title").hide('slow');
        $("#table-object").show('slow');
        $("#title > option").prop("selected", "");
        $("#title").trigger("change");
        $('.title').val('');
        $("#checkbox").prop('checked', false);
    } else {
        $("#object-add").show('slow');
        $("#object-unit").hide('slow');
        $("#object-title").show('slow');
        $("#table-object").show('slow');
        $("#object-unit select[name=unit_id\\[\\]]").val(null).trigger('change');
    }
});

</script>
