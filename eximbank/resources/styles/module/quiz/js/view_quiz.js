$(document).ready(function() {
    var tempalate = document.getElementById('question-template').innerHTML;
    var template_chosen = document.getElementById('answer-template-chosen').innerHTML;
    var template_essay = document.getElementById('answer-template-essay').innerHTML;
    var template_answer_matching = document.getElementById('answer-template-matching').innerHTML;
    var template_qqcategory = document.getElementById('qqcategory-template').innerHTML;
    var answer_text = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j'];
    var current_page = parseInt(get_query_string('page'));

    if (current_page < 1 || isNaN(current_page)) {
        current_page = 1;
    }

    pageloadding(1);
    load_questions(current_page);

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
        disabled_button(1);
        current_page += 1;
        load_questions(current_page);
    });

    $(".button-back").on('click', function () {
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
                disabled_button(0);
                show_message('Đã hết bài thi', 'warning');
                if (current_page > 1) {
                    current_page -= 1;
                }
                return false;
            }

            let rhtml = '';
            $.each(data.rows, function (i, item) {
                if (qqcategory['num_'+ (item.qindex-1)]) {
                    rhtml += replacement_template(template_qqcategory, {
                        'name': qqcategory['num_'+ (item.qindex-1)],
                        'percent': qqcategory['percent_'+ (item.qindex-1)],
                    });
                }
                let prompt = '';
                let answers = '';
                if (item.type === 'matching'){
                    let anwsers = item.answers;
                    let index = 0;
                    $.each(anwsers, function (i, a) {
                        let anwser = replacement_template(template_answer_matching, {
                            'id': a.id,
                            'qid': item.id,
                            'index': index,
                            'index_text': answer_text[index],
                            'title': a.title,
                            'matching': a.matching_answer,
                        });

                        answers += anwser;
                        index++;
                    });

                }
                if (item.type === 'multiple-choise') {
                    prompt = 'Chọn một đáp án:';
                    if (item.multiple == 1) {
                        prompt = 'Chọn một hoặc nhiều đáp án:';
                    }

                    let anwsers = item.answers;
                    let index = 0;
                    $.each(anwsers, function (i, a) {
                        let anwser = replacement_template(template_chosen, {
                            'id': a.id,
                            'qid': item.id,
                            'index': index,
                            'index_text': answer_text[index],
                            'title': a.title,
                            'input_type': (item.multiple == 1) ? 'checkbox' : 'radio',
                            'qindex': item.qindex,
                            'checked': (a.selected == 1) ? 'checked' : '',
                        });

                        answers += anwser;
                        index++;
                    });
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
                let newtemp = replacement_template(tempalate, {
                    'qid': item.id,
                    'index': item.qindex,
                    'max_score': item.max_score,
                    'name': item.name,
                    'answers': answers,
                    'prompt': prompt,
                    'image_drag_drop': item.image_drag_drop ? '<img src="'+ item.image_drag_drop +'" class="border mb-2" id="image_drag_drop">' : '',
                });

                rhtml += newtemp;

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

            return false;
        }).fail(function(data) {
            alert('Không thể tải lên câu hỏi');
            return false;
        });
    }

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
    
    function pageloadding(status) {
        if (status == 1) {
            $("#loading").show();
        }
        else {
            $("#loading").hide();
        }
    }
});