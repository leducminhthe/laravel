<style>
    table video {
        width: 50%;
        height: auto;
    }

    table img {
        width: 50% !important;
        height: auto !important;
    }

    table p{
        color: unset;
    }
</style>
<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('backend.add_questionlib') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <form action="" id="form-search">
                    <div class="form-group">
                        <label>{{ trans('lamenu.category') }}</label>
                        <select name="category_id" id="category_id" class="form-control select23" data-placeholder="-- {{ trans('lamenu.category') }} --">
                            <option value=""></option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }} ({{ $count_question($category->id) }})</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <form action="" method="post" class="form-ajax" id="form-add-question" data-success="success_submit">
                    <div class="form-group">
                        <table class="tDefault table table-hover bootstrap-table">
                            <thead>
                                <tr>
                                    <th data-field="index" data-formatter="index_formatter" data-width="3%" data-align="center">#</th>
                                    <th data-field="state" data-checkbox="true" data-width="3%"></th>
                                    <th data-field="name">{{ trans('latraining.question') }}</th>
                                    <th data-field="level" data-formatter="level_formatter" data-width="10%" data-align="center">Mức độ</th>
                                    <th data-field="type" data-formatter="type_formatter" data-align="center" data-width="20%">{{ trans('lasurvey.question_type') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn button-save" disabled><i class="fa fa-plus-circle"></i> {{ trans('latraining.add_question_select') }}</button>
                <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans('labutton.close') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">

    function index_formatter(value, row, index) {
        return (index+1);
    }

    function type_formatter(value, row, index) {
        switch(value){
            case('multiple-choise'):
                if (row.multiple == 1) {
                    var multiple = '(chọn nhiều)';
                } else {
                    var multiple = '(chọn 1)';
                }
                return "{{trans('lasurvey.choice')}} "+ multiple
            case('essay'): return "{{trans('lasurvey.essay')}}";
            case('matching'): return "{{trans('latraining.sentence_matching')}}";
            case('fill_in'): return "{{trans('latraining.fill_blank')}}";
            case('fill_in_correct'): return "{{trans('latraining.fill_correct_answer')}}";
            case('select_word_correct'): return "{{trans('latraining.choose_missing_word')}}";
            case('drag_drop_marker'): return "{{trans('latraining.drag_marker')}}";
            case('drag_drop_image'): return "{{trans('latraining.drag_image')}}";
            case('drag_drop_document'): return "{{trans('latraining.drag_text')}}";
            default: return '';
        }
    }

    function level_formatter(value, row, index) {
        switch(row.difficulty){
            case('D'): return "Dễ";
            case('TB'): return "Trung Bình";
            case('K'): return "Khó";
            default: return '';
        }
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.quiz_template.question.getdata_question', ['id' => $quiz_id]) }}',
        search: true,
        page_size:10
    });

    $(".select23").select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
    });

    $('#form-add-question').on('change', function(){
        if($('input[name=btSelectAll], input[name=btSelectItem]').is(':checked')){
            $(".button-save").prop('disabled', false);
        }
    });

    $(".button-save").on('click', function() {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        let button = $(this);
        let icon = button.find('i').attr('class');

        button.find('i').attr('class', 'fa fa-spinner fa-spin');
        button.prop("disabled", true);

        $.ajax({
            type: 'POST',
            url: "{{ route('module.quiz_template.question.save_category_question', ['id' => $quiz_id]) }}",
            dataType: 'json',
            data: {
                ids: ids
            },
        }).done(function(data) {

            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);

            table.refresh();
            $("#app-modal #myModal").modal('hide');
            window.location = "";
            return false;
        }).fail(function(data) {
            show_message('Chưa chọn câu hỏi', 'error');
            button.find('i').attr('class', icon);
            button.prop("disabled", false);
            return false;
        });

    });
    $('#category_id').on('change',function () {
        table.refresh();
    });
    function success_submit(form) {
        $("#app-modal #myModal").modal('hide');
        table.refresh();
    }
</script>

