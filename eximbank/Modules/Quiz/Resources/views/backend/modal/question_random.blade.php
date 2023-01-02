<div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('module.quiz.question.save_question_random', ['id' => $quiz_id]) }}" method="post" class="form-ajax" data-success="success_submit">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('backend.add_random_question') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('lamenu.category') }}</label>
                        <select name="category_id" class="form-control select23" data-placeholder="-- {{ trans('lamenu.category') }} --">
                            <option value=""></option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }} ({{ $count_question($category->id) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="radio-inline"><input type="radio" name="type" value="1" id="check_type_1"> Số câu</label>
                        <label class="radio-inline"><input type="radio" name="type" value="2" id="check_type_2"> Mức độ</label>
                    </div>
                    <div class="form-group" id="type_1">
                        <label>{{ trans('backend.number_random_questions') }}</label>
                        <input name="random_question" class="form-control is-number" type="text">
                    </div>
                    <div class="form-group" id="type_2">
                        <label>{{ trans('backend.number_random_questions') }}</label>
                        <div class="row">
                            <div class="col-4">
                                <input name="random_question_d" class="form-control is-number" type="text" placeholder="Dễ">
                            </div>
                            <div class="col-4">
                                <input name="random_question_tb" class="form-control is-number" type="text" placeholder="Trung bình">
                            </div>
                            <div class="col-4">
                                <input name="random_question_k" class="form-control is-number" type="text" placeholder="Khó">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</button>
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans('labutton.close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
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

    $('#check_type_1').prop('checked', true);
    $('#type_2').hide();

    $('#check_type_1').on('click', function(){
        $('input[name=random_question_d]').val('');
        $('input[name=random_question_tb]').val('');
        $('input[name=random_question_k]').val('');

        $('#type_1').show();
        $('#type_2').hide();
    });

    $('#check_type_2').on('click', function(){
        $('input[name=random_question]').val('');

        $('#type_1').hide();
        $('#type_2').show();
    });
</script>

