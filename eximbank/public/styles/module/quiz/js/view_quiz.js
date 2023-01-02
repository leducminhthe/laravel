$(document).ready(function() {
    var tempalate = document.getElementById('question-template').innerHTML;
    var template_chosen = document.getElementById('answer-template-chosen').innerHTML;
    var template_essay = document.getElementById('answer-template-essay').innerHTML;
    var template_answer_matching = document.getElementById('answer-template-matching').innerHTML;
    var template_qqcategory = document.getElementById('qqcategory-template').innerHTML;
    var template_fill_in = document.getElementById('fill-in-template').innerHTML;
    var template_fill_in_correct = document.getElementById('fill-in-correct-template').innerHTML;
    var template_correct_answer = document.getElementById('correct-answer-template-chosen').innerHTML;
    var template_matching_feedback = document.getElementById('matching-feedback-template').innerHTML;
    var template_select_word_correct = document.getElementById('select_word_correct').innerHTML;
    var template_drag_drop_marker = document.getElementById('drag_drop_marker').innerHTML;
    var template_drag_drop_document = document.getElementById('drag_drop_document').innerHTML;
    var template_drag_drop_image = document.getElementById('drag_drop_image').innerHTML;

    var answer_text = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'];
    var current_page = parseInt(get_query_string('page'));

    if (current_page < 1 || isNaN(current_page)) {
        current_page = 1;
    }

    pageloadding(1);
    load_questions(current_page);

    var numItems = $("#info-number-question").find('.question-selected').length;
    $('#num-question-selected').html(numItems);

    $("#questions").on('change', '.selected-answer', function () {
        let quiz_id = $(this).closest('.question-item').data('qid');
        $("#select-q"+ quiz_id).addClass('question-selected');
    });

    $("#questions").on('click', '.add-comment', function () {
        let form_comment = $(this).closest('.form-grading').find('.form-comment');
        if (form_comment.is(':visible')) {
            form_comment.addClass('d-none');
        }
        else {
            form_comment.removeClass('d-none');
        }
    });


    $(".button-next").on('click', function () {
        pageloadding(1);
        disabled_button(1);
        current_page += 1;
        load_questions(current_page);
    });

    $(".button-back").on('click', function () {
        pageloadding(1);
        disabled_button(1);
        if (current_page > 1) {
            current_page -= 1;
            load_questions(current_page);
        }
    });

    $(".select-question").on('click', function () {
        let quiz_id = $(this).data('id');
        let question_page = $(this).data('quiz-page');
        if (question_page == current_page) {
            let elmnt = document.getElementById("q"+ quiz_id);
            elmnt.scrollIntoView({
                behavior: 'smooth'
            });
        }
        else {
            pageloadding(1);
            load_questions(question_page, "q"+ quiz_id);
            current_page = question_page;
        }
    });

    function load_questions(page, scroll = null) {
        let text_page = '?page='+ page;
        $.ajax({
            type: 'POST',
            url: quiz_url + text_page,
            dataType: 'json',
            data: {}
        }).done(function(data) {
            if (data.rows.length <= 0) {
                pageloadding(0);
                disabled_button(0);
                show_message('Đã hết bài thi', 'warning');
                if (current_page > 1) {
                    current_page -= 1;
                }
                return false;
            }

            let rhtml = '';
            $.each(data.rows, function (i, item) {
                /*if (qqcategory['num_'+ (item.qindex-1)]) {
                    rhtml += replacement_template(template_qqcategory, {
                        'name': qqcategory['num_'+ (item.qindex-1)],
                        'percent': qqcategory['percent_'+ (item.qindex-1)],
                    });
                }*/
                let ques_name = item.name;
                let question = item.question;
                let feedback_ques = item.feedback_ques;
                let answer_matching = item.answer_matching;
                let answers = '';
                let correct = '';
                let prompt = '';
                var matching_answer = [];
                var select_word_answer = {};
                let image_drag_drop = '';
                let drop_image = '';

                if (item.type === 'matching'){
                    let anwsers = item.answers;
                    let index = 0;

                    $.each(anwsers, function (i, a) {
                        matching_answer += '<option value="'+ a.matching_answer +'" >' + a.matching_answer +'</option>';
                    });

                    $.each(anwsers, function (i, a) {
                        let anwser = replacement_template(template_answer_matching, {
                            'id': a.id,
                            'qid': item.id,
                            'index': index,
                            'index_text': answer_text[index],
                            'title': a.title,
                            'option': matching_answer,
                            'matching': item.matching ? item.matching[a.id] : '',
                            'correct': (item.matching && a.matching_answer) ? (item.matching[a.id] == a.matching_answer ? '<i class="text-success fa fa-check"></i>' : '<i class="text-danger fa fa-times"></i>') : '',
                        });

                        correct += a.title + ' ' + a.matching_answer + '. ';

                        answers += anwser;
                        index++;
                    });

                    let feedback = '';
                    if (feedback_ques){
                        feedback += '<div class="card"><div class="card-header bg-info text-white"> Phản hồi chung </div>';
                        $.each(feedback_ques, function (i, a) {
                            feedback += '<input type="text" class="form-control" disabled value="'+a+'">';
                        });
                        feedback += '</div>';
                    }
                    let matching_feedback = replacement_template(template_matching_feedback,{
                        'feedback': feedback,
                        'correct_answer': correct,
                    });
                    answers += matching_feedback;
                }
                if (item.type === 'multiple-choise') {
                    prompt = 'Chọn một đáp án:';
                    if (item.multiple == 1) {
                        prompt = 'Chọn một hoặc nhiều đáp án:';
                    }

                    let anwsers = item.answers;
                    let index = 0;
                    let correct = '';
                    let selected = item.answer;
                    let answer_horizontal = parseInt(item.answer_horizontal);
                    if (answer_horizontal != 0){
                        answers += '<div class="row">';
                    }
                    $.each(anwsers, function (i, a) {
                        let anwser = replacement_template(template_chosen, {
                            'id': a.id,
                            'qid': item.id,
                            'index': index,
                            'index_text': answer_text[index],
                            'title': (a.title != null) ? a.title : '',
                            'input_type': (item.multiple == 1) ? 'checkbox' : 'radio',
                            'qindex': item.qindex,
                            'checked': selected ? (selected.includes(a.id.toString()) ? 'checked' : '') : '',
                            'correct': item.correct_answers ? (item.correct_answers.includes(a.id)) ? '<i style="width: 15px" class="text-success fa fa-check mr-2"></i>' : '<i style="width: 15px" class="text-danger fa fa-times mr-2"></i>' : '',
                            'feedback': a.feedback_answer ? '<textarea type="text" class="form-control" disabled>'+a.feedback_answer+'</textarea><p></p>' : '',
                            'image_answer': a.image_answer ? '<img src="'+a.image_answer+'" class="w-100 img-responsive" />' : '',
                        });

                        if (item.correct_answers){
                            if (item.correct_answers.includes(a.id)){
                                correct += (a.title != null ? a.title : '');
                                correct += a.image_answer ? '<br> <img src="'+a.image_answer+'" class="w-100 img-responsive" />' : '';
                            }
                        }

                        if (answer_horizontal != 0){
                            answers += '<div class="col-'+ (12/answer_horizontal) +' p-1">' + anwser +'</div>';
                        }else{
                            answers += anwser;
                        }
                        index++;
                    });

                    if (answer_horizontal != 0) {
                        answers += '</div>';
                    }

                    let correct_answer = replacement_template(template_correct_answer, {
                        'correct_answer': correct,
                    });
                    answers += correct_answer;
                }
                if (item.type === 'essay') {
                    answers = replacement_template(template_essay, {
                        'id': item.id,
                        'qid': item.id,
                        'text_essay': (item.text_essay ? item.text_essay : ''),
                        'max_score': item.max_score,
                        'score': item.score,
                        'grading_comment': (item.grading_comment ? item.grading_comment : '')
                    });
                }
                if (item.type === 'fill_in') {
                    let anwsers = item.answers;
                    let index = 0;

                    $.each(anwsers, function (i, a) {
                        let anwser = replacement_template(template_fill_in, {
                            'id': a.id,
                            'qid': item.id,
                            'index': index,
                            'index_text': answer_text[index],
                            'title': a.title,
                            'qindex': item.qindex,
                            'text_essay': item.text_essay ? (item.text_essay[i] ? item.text_essay[i] : '') : '',
                            'feedback': a.feedback_answer ? '<textarea type="text" class="form-control" disabled>'+a.feedback_answer+'</textarea><p></p>' : '',
                        });

                        answers += anwser;
                        index++;
                    });
                }

                if (item.type === 'fill_in_correct') {
                    let anwsers = item.answers;
                    let index = 0;

                    $.each(anwsers, function (i, a) {
                        let anwser = replacement_template(template_fill_in_correct, {
                            'id': a.id,
                            'qid': item.id,
                            'index': index,
                            'index_text': answer_text[index],
                            'title': a.title,
                            'qindex': item.qindex,
                            'text_essay': item.text_essay ? (item.text_essay[i] ? item.text_essay[i] : '') : '',
                        });

                        answers += anwser;
                        index++;
                    });
                }

                if(item.type == 'drag_drop_marker'){
                    image_drag_drop = '<img src="'+ item.image_drag_drop +'" class="border mb-2 image_drag_drop">';

                    let anwsers = item.answers;
                    let answer_selected = item.answer;

                    let answer_drag_marker = '<div class="mt-2 list_mark_text">';
                    $.each(anwsers, function (i, a) {
                        var drag_drop_marker_val = (answer_selected && answer_selected[a.id] != 'null') ? answer_selected[a.id] : '';
                        var left = drag_drop_marker_val ? drag_drop_marker_val.split(",")[0] : 0;
                        var top = drag_drop_marker_val ? drag_drop_marker_val.split(",")[1] : 0;
                        var style = drag_drop_marker_val ? 'style="position: absolute; left: '+left+'px; top: '+top+'px;"' : '';

                        answer_drag_marker += '<span class="m-1 p-2 border mark_text" draggable="true" '+style+' >'+ a.title +'</span>';
                    });
                    answer_drag_marker += '</div>';

                    let anwser = replacement_template(template_drag_drop_marker, {
                        'name': answer_drag_marker,
                    });

                    answers += anwser;
                }

                if(item.type == 'drag_drop_image'){
                    image_drag_drop = '<img src="'+ item.image_drag_drop +'" class="border mb-2">';

                    let anwsers = item.answers;
                    let answer_array = item.answer;
                    let answer_selected = {};
                    let i_group = 1;
                    let answer_id_selected = [];

                    if(answer_array){
                        $.each(answer_array, function(i, a){
                            if(a){
                                $.each(anwsers, function (ii, aa) {
                                    if(aa.id == a){
                                        if(aa.image_answer){
                                            answer_selected[i] = '<img src="'+aa.image_answer+'" class="m-1 p-2 mark_text" draggable="true" style="max-width:150px;" data-qid="'+item.id+'" data-id="'+aa.id+'" />';
                                        } else{
                                            answer_selected[i] = '<span class="m-1 p-2 mark_text" draggable="true" data-qid="'+item.id+'" data-id="'+aa.id+'">'+ aa.title +'</span>';
                                        }
                                    }
                                });
                                answer_id_selected.push(a);
                            }
                        });
                    }

                    let answer_drag_marker = '<div class="mt-2 list_mark_text">';
                    $.each(anwsers, function (i, a) {
                        if(a.select_word_correct > i_group){
                            i_group += 1;
                            answer_drag_marker += '<br/><br/>';
                        }

                        if(answer_id_selected){
                            if(!answer_id_selected.includes(a.id.toString())){
                                if(a.image_answer){
                                    answer_drag_marker += '<img src="'+a.image_answer+'" class="m-1 p-2 border mark_text" draggable="true" style="max-width:150px;" />';
                                } else{
                                    answer_drag_marker += '<span class="m-1 p-2 border mark_text" draggable="true">'+ a.title +'</span>';
                                }
                            }
                        }else{
                            if(a.image_answer){
                                answer_drag_marker += '<img src="'+a.image_answer+'" class="m-1 p-2 border mark_text" draggable="true" style="max-width:150px;" />';
                            } else{
                                answer_drag_marker += '<span class="m-1 p-2 border mark_text" draggable="true">'+ a.title +'</span>';
                            }
                        }

                        if(a.marker_answer){
                            var left = a.marker_answer.split(",")[0];
                            var top = a.marker_answer.split(",")[1];
                            var style = 'style="position: absolute; left: '+left+'px; top: '+top+'px;"';

                            drop_image += '<span class="m-1 p-2 border w-auto drop_document" '+style+'>';
                            if(answer_selected && answer_selected[a.id]){
                                drop_image += answer_selected[a.id];
                            }
                            drop_image += '</span>';
                        }
                    });
                    answer_drag_marker += '</div>';

                    let anwser = replacement_template(template_drag_drop_image, {
                        'name': answer_drag_marker,
                    });

                    answers += anwser;
                }

                if(item.type == 'drag_drop_document'){
                    let anwsers = item.answers;
                    let answer_array = item.answer;
                    let answer_selected = {};
                    let obj = {};
                    let i_group = 1;
                    let answer_id_selected = [];

                    if(answer_array){
                        $.each(answer_array, function(i, a){
                            if(a){
                                $.each(anwsers, function (ii, aa) {
                                    if(aa.id == a){
                                        answer_selected[i] = '<span class="m-1 p-2 mark_text" draggable="true">'+ aa.title +'</span>';
                                    }
                                });

                                answer_id_selected.push(a);
                            }
                        });
                    }

                    let answer_drag_marker = '<div class="mt-5 list_mark_text">';
                    $.each(anwsers, function (i, a) {
                        if(a.select_word_correct > i_group){
                            i_group += 1;
                            answer_drag_marker += '<br/><br/>';
                        }

                        if(answer_id_selected){
                            if(!answer_id_selected.includes(a.id.toString())){
                                answer_drag_marker += '<span class="m-1 p-2 border mark_text" draggable="true">'+ a.title +'</span>';
                            }
                        }else{
                            answer_drag_marker += '<span class="m-1 p-2 border mark_text" draggable="true">'+ a.title +'</span>';
                        }

                        if(a.correct_answer>0) {
                            let bb = `\[\[` + a.correct_answer + `\]\]`;
                            obj[bb] = '<span class="m-1 p-2 border w-auto drop_document">';
                            if(answer_selected && answer_selected[a.correct_answer]){
                                obj[bb] += answer_selected[a.correct_answer];
                            }
                            obj[bb] += '</span>';
                        }
                    });
                    answer_drag_marker += '</div>';

                    ques_name = replacement_select_word_correct(item.name, obj);

                    let anwser = replacement_template(template_drag_drop_document, {
                        'name': answer_drag_marker,
                    });

                    answers += anwser;
                }

                if (item.type === 'select_word_correct') {
                    let anwsers = item.answers;
                    let index = 0;
                    let selected = '';
                    let obj = {};
                    let obj2 ={};
                    let disabled = '';
                    let selectAnswerCorrect  = groupBy(anwsers,v => v.select_word_correct);

                    $.each(selectAnswerCorrect, function (i, a) {
                        let str = `<select name="q_${item.id}[]" ${disabled} class="selected-answer" data-answer="${item.question_id}">`;
                        $.each(a,function (ii,e) {
                            str+= '<option value="'+ e.id +'" '+ selected+'>' + e.title +'</option>';

                            if(e.correct_answer>0) {
                                let bb = `\[\[` + e.select_word_correct + `\]\]`;
                                obj2[bb]= `\[\[` +e.select_word_correct+ `\]\]`;
                            }
                        })
                        str += '</select>';
                        let aa = `\[\[`+i+`\]\]`;
                        obj[aa] = str;
                    });
                    //console.log(obj2);
                    let rename= replacement_select_word_correct(item.name,obj2);

                    let name= replacement_select_word_correct(rename,obj);
                    // replacement_select_word_correct(item.name,);
                    let anwser = replacement_template(template_select_word_correct, {
                        'id': 1,
                        'qid': item.id,
                        'index': index,
                        'name': name,
                        'option': select_word_answer,
                    });

                    let newtemp = replacement_template(tempalate, {
                        'qid': item.id,
                        'index': (page > 1) ? ((i + 1) + (parseInt(questions_perpage) * (page - 1))) : (i + 1),
                        'max_score': item.max_score,
                        'name': anwser,
                        'answers': '',
                        'prompt': prompt,
                        'class_flag': (item.flag && item.flag == 1 ? "fa-flag-red" : ""),
                        'flag': item.flag ? item.flag : 0,
                    });
                    rhtml += newtemp;
                }else {

                    let newtemp = replacement_template(tempalate, {
                        'qid': item.id,
                        'index': (page > 1) ? ((i + 1) + (parseInt(questions_perpage) * (page - 1))) : (i + 1),
                        'max_score': item.max_score,
                        'name': ques_name,
                        'answers': answers,
                        'prompt': prompt,
                        'image_drag_drop': image_drag_drop,
                        'drop_image': drop_image,
                    });

                    rhtml += newtemp;
                }

            });

            window.history.pushState({page: page}, "", text_page);
            document.getElementById('questions').innerHTML = "";
            document.getElementById('questions').innerHTML = rhtml;
            pageloadding(0);
            disabled_button(0);
            if (current_page == 1) {
                $(".button-back").prop('disabled', true);
            } else {
                $(".button-back").prop('disabled', false);
            }

            if (scroll) {
                let elmnt = document.getElementById(scroll);
                elmnt.scrollIntoView({
                    behavior: 'smooth'
                });
            }

            var image_drag_drop_list = document.querySelectorAll(".image_drag_drop");
            var mark_text_arr = document.querySelectorAll('.mark_text');
            var drop_document_arr = document.querySelectorAll('.drop_document');
            var current_target = null;

            if(mark_text_arr.length > 0){
                mark_text_arr.forEach(mark_text => {
                    mark_text.addEventListener('dragstart', function(e){
                        current_target = this;
                    });
                });
            }

            if(image_drag_drop_list.length > 0){
                image_drag_drop_list.forEach(image_drag_drop => {
                    image_drag_drop.addEventListener('dragover', function(e){
                        e.preventDefault();

                        current_target.style.position = 'absolute';
                        current_target.style.left = e.offsetX + 'px';
                        current_target.style.top = e.offsetY + 'px';
                    });

                    image_drag_drop.addEventListener('drop', function(e){
                        this.closest('#image-area').appendChild(current_target);
                    });
                });

            }

            if(drop_document_arr.length > 0){
                drop_document_arr.forEach(drop_document => {
                    drop_document.addEventListener('dragover', function(e){
                        e.preventDefault();
                    });

                    drop_document.addEventListener('drop', function(e){
                        current_target.classList.remove('border');

                        if (!drop_document.querySelector('.mark_text')) {
                            this.appendChild(current_target);
                        }else{
                            this.children[0].classList.add('border');
                            this.closest('.question-item').querySelector('.list_mark_text').appendChild(this.children[0]);

                            this.appendChild(current_target);
                        }
                    });
                });
            }

            return false;
        }).fail(function(data) {
            alert('Không thể tải lên câu hỏi');
            return false;
        });
    }

    const groupBy = (x,f)=>x.reduce((a,b)=>((a[f(b)]||=[]).push(b),a),{});

    function disabled_button(status) {
        if (status == 1) {
            $(".button-next").prop('disabled', true);
            $(".button-back").prop('disabled', true);
        }
        else {
            $(".button-next").prop('disabled', false);
            $(".button-back").prop('disabled', false);
        }
    }

    function get_query_string(str_query) {
        let urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(str_query);
    }

    function replacement_template(template, data){
        return template.replace(
            /{(\w*)}/g,
            function( m, key ){
                return data.hasOwnProperty( key ) ? data[ key ] : "";
            }
        );
    }

    function replacement_select_word_correct(template,data) {
        return template.replace(
            /\[\[([0-9]+)\]\]/g,
            function( m, key ){
                return data.hasOwnProperty( m ) ? data[ m ] : "";
            }
        );
    }

    function pageloadding(status) {
        if (status == 1) {
            $("#loading").show();
        }
        else {
            $("#loading").hide();
        }
    }
});
