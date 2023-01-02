<div class="row">
    <div class="col-md-9">
    @if($permission_save)
        <form method="post" action="{{ route('module.online.save_object', ['id' => $model->id]) }}" class="form-ajax" id="form-object" data-success="submit_success_object">
            <div class="box-title">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label>{{trans('latraining.choose_unit')}} <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-9">
                        @include('backend.form_choose_unit', ['multiple' => 1])
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="title_rank_id">{{ trans('lacategory.title_level') }}</label>
                    </div>
                    <div class="col-md-9">
                        <select name="title_rank_id" id="title_rank_id" class="load-title-rank form-control" data-placeholder="-- {{ trans('lacategory.title_level') }} --">
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="title_id">{{ trans('laprofile.title') }}</label>
                    </div>
                    <div class="col-md-9">
                        <select id="title" class="load-title form-control" multiple data-title_rank_id="" data-placeholder="-- {{ trans('laprofile.title') }} --">
                            @foreach($titles as $title)
                                <option value="{{ $title->id }}">{{ $title->name }}</option>
                            @endforeach
                        </select>
                        <input type="checkbox" id="checkbox" >{{trans('latraining.select_all')}}
                        <div class="noty_choose_all_title"></div>
                    </div>
                    <input type="hidden" name="check_all_title" value="0">
                    <input type="hidden" name="title" class="form-control title" value="">
                </div>
            </div>

            {{-- <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('latraining.type_object')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <label class="radio-inline"><input type="radio" name="type" value="1">{{trans('latraining.obligatory')}}</label>
                    <label class="radio-inline"><input type="radio" name="type" value="2">{{trans('latraining.register')}}</label>
                </div>
            </div> --}}
            <input type="hidden" name="type" value="1">

            <div class="form-group row">
                <div class="col-sm-3 control-label"></div>
                <div class="col-md-9">
                    @if($model->lock_course == 0)
                    <button type="submit" class="btn" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{trans('labutton.add_object')}}</button>
                    @endif
                </div>
            </div>
        </form>
    @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="text-right">
            @if(\Modules\Online\Entities\OnlinePermission::saveCourse($model) && $model->lock_course == 0)
            <button id="delete-object" class="btn"><i class="fa fa-trash"></i> {{trans('labutton.delete')}}</button>
            @endif
        </div>
        <p></p>
        <table class="tDefault table table-hover bootstrap-table text-nowrap" id="table-object">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-align="center" data-width="3%" data-formatter="stt_formatter">#</th>
                    <th data-field="title_name">{{trans('latraining.title')}}</th>
                    <th data-field="unit_name">{{trans('latraining.unit')}}</th>
                    <th data-align="center" data-field="type" data-width="10%" data-formatter="type_formatter">{{trans('latraining.type_object')}}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    function type_formatter(value, row, index) {
        return value == 1 ? 'Bắt buộc' : '{{ trans("backend.register") }}';
    }

    function stt_formatter(value, row, index) {
        return (index + 1);
    }

    var table_object = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.online.get_object', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.online.remove_object', ['id' => $model->id]) }}',
        detete_button: '#delete-object',
        table: '#table-object'
    });

    $('#title_rank_id').on('change', function () {
        var title_rank_id = $('#title_rank_id option:selected').val();

        $("#title").empty();
        $("#title").data('title_rank_id', title_rank_id);
        $('#title').trigger('change');
    })

    $('#title').on('change', function () {
        var title = $("#title option:selected").map(function(){return $(this).val();}).get();
        $('.title').val(title);
    });

    $("#checkbox").click(function(){
        if($("#checkbox").is(':checked') ){
            $('input[name=check_all_title]').val(1);
            $('.noty_choose_all_title').html('<span class="text-danger">Chọn chức danh loại bỏ</span>')
        } else{
            $('input[name=check_all_title]').val(0);
            $('.noty_choose_all_title').html('')
        }
    });

    $(".object-type").on('change', function () {
        let type = parseInt($(this).val());
        if (type == 1) {
            $(".box-title").removeClass('box-hidden');
            $(".box-unit").addClass('box-hidden');
            $(".box-area").addClass('box-hidden');
        }

        if (type == 2) {
            $(".box-unit").removeClass('box-hidden');
            $(".box-title").addClass('box-hidden');
            $(".box-area").addClass('box-hidden');
        }

        if (type == 3) {
            $(".box-area").removeClass('box-hidden');
            $(".box-unit").addClass('box-hidden');
            $(".box-title").addClass('box-hidden');
        }
    });

    function submit_success_object(form) {
        $("#form-object #title_rank_id").val(null).trigger('change');
        $("#form-object #title").val(null).trigger('change');
        $(".check-unit").prop('checked', false);
        $("input[name=type]").prop('checked', false);
        table_object.refresh();
    }

    var openedClass = 'uil-minus uil';
    var closedClass = 'uil uil-plus';

    $('#tree-unit').on('click', '.tree-item', function (e) {
        var id = $(this).data('id');

        if ($(this).closest('.item').find('i:first').hasClass(openedClass)){
            $('#list'+id).find('ul').remove();
        }else{
            $.ajax({
                type: 'POST',
                url: "{{ route('backend.category.unit.tree_folder.get_child') }}",
                dataType: 'json',
                data: {
                    id: id
                }
            }).done(function(data) {
                let rhtml = '';

                rhtml += '<ul class="ml-3">';
                $.each(data.childs, function (i, item){

                    rhtml += '<li>';
                    rhtml += '<div class="item">';
                    rhtml += '<i class="uil uil-plus"></i> <input type="checkbox" name="unit[]" class="check-unit" data-id="'+ item.id +'" value="'+ item.id +'"> ';
                    rhtml += '<a href="javascript:void(0)" data-id="'+ item.id +'" class="tree-item"> ' + item.name + ' (' + data.count_child[item.id] + ') </a>';
                    rhtml += '</div>';
                    rhtml += '<div id="list'+ item.id +'"></div>';
                    rhtml += '</li>';
                });
                rhtml += '</ul>';

                document.getElementById('list'+id).innerHTML = '';
                document.getElementById('list'+id).innerHTML = rhtml;
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }

        if (this == e.target) {
            var icon = $(this).closest('.item').children('i:first');
            icon.toggleClass(openedClass + " " + closedClass);
            $(this).children().children().toggle();
        }
    });

    $('#tree-unit').on('click', '.check-unit', function (e) {
        var id = $(this).data('id');

        if($(this).is(":checked")){
            $(this).prop('checked', true);
            $.ajax({
                type: 'POST',
                url: "{{ route('module.online.get_child', ['id' => $model->id]) }}",
                dataType: 'json',
                data: {
                    id: id
                }
            }).done(function(data) {
                $.each(data.childs, function (i, item){
                    $('#list'+id).load(data.page_child[item.id]);
                });
            }).fail(function(data) {
                show_message('{{ trans('laother.data_error') }}', 'error');
                return false;
            });
        }else if($(this).is(":not(:checked)")){
            $(this).prop('checked', false);
            $('#list'+id).find('.check-unit').attr('checked', false);
        }

        if (this == e.target) {
            var icon = $(this).closest('.item').children('i:first');
            icon.toggleClass(openedClass + " " + closedClass);
            $(this).children().children().toggle();
        }
    });
</script>
