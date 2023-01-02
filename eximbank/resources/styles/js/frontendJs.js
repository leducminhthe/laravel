var tab = $('#languages_menu_bottom').attr("data-processing")

// LÊN ĐẦU TRANG
window.onscroll = function() {scrollFunction()};
function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        $('.btn_to_top').show();
    } else {
        $('.btn_to_top').hide();
    }
}

var tabs = $('#element_app').attr("data-tabs");
var check_navigate = $('#element_app').attr("data-check_navigate");
var check_session_navigate = $('#element_app').attr("data-check_session_navigate");
var url_case_1 = $('#element_app').attr("data-url_case_1");
var url_case_2 = $('#element_app').attr("data-url_case_2");
var url_case_3 = $('#element_app').attr("data-url_case_3");
var url_case_4 = $('#element_app').attr("data-url_case_4");
var url_case_5 = $('#element_app').attr("data-url_case_5");
var url_case_6 = $('#element_app').attr("data-url_case_6");
var url_case_7 = $('#element_app').attr("data-url_case_7");
var url_case_8 = $('#element_app').attr("data-url_case_8");
var url_case_9 = $('#element_app').attr("data-url_case_9");
var save_experience_navigate = $('#element_app').attr("data-save_experience_navigate");
var update_survey_popup = $('#element_app').attr("data-update_survey_popup");
var check_survey_popup = $('#element_app').attr("data-check_survey_popup");

if (check_navigate != 0 && !check_session_navigate) {
    $('#modal_show_config').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function redirectConfig(params) {
    var url = $('#link_config').val();
    window.location = url;
}

function setConfig(type) {
    switch (type) {
        case 1:
            var url = url_case_1;
            $('#link_config').val(url);
            break;
        case 2:
            var url = url_case_2;
            $('#link_config').val(url);
            break;
        case 3:
            var url = url_case_3;
            $('#link_config').val(url);
            break;
        case 4:
            var url = url_case_4;
            $('#link_config').val(url);
            break;
        case 5:
            var url = url_case_5;
            $('#link_config').val(url);
            break;
        case 6:
            var url = url_case_6;
            $('#link_config').val(url);
            break;
        case 7:
            var url = url_case_7;
            $('#link_config').val(url);
            break;
        case 8:
            var url = url_case_8;
            $('#link_config').val(url);
            break;
        case 9:
            var url = url_case_9;
            $('#link_config').val(url);
            break;
        default:
            break;
    }
    $('#modal_show_config').modal('hide');
    $('#modal_set_config').modal({
        backdrop: 'static',
        keyboard: false
    })
    saveCountNavigate();
}

function saveCountNavigate() {
    $.ajax({
        url: save_experience_navigate,
        type: 'post',
        data: {
            id: check_navigate,
        }
    }).done(function(data) {
        return false;
    }).fail(function(data) {
        show_message('Lỗi dữ liệu', 'error');
        return false;
    });
}

$('.datetimepicker').datetimepicker({
    locale:'vi',
    format: 'DD/MM/YYYY HH:mm'
});
$('.datetimepicker-timeonly').datetimepicker({
    locale:'vi',
    format: 'LT'
});
$('.datepicker').datetimepicker({
    locale:'vi',
    format: 'DD/MM/YYYY'
});

$('.select2').select2({
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

$('.load-user-other').select2({
    allowClear: true,
    dropdownAutoWidth : true,
    width: '100%',
    placeholder: function(params) {
        return {
            id: null,
            text: params.placeholder,
        }
    },
    ajax: {
        method: 'GET',
        url: base_url + '/load-ajax/loadUserOther',
        dataType: 'json',
        data: function (params) {
            var query = {
                search: $.trim(params.term),
                page: params.page,
            };
            return query;
        }
    }
});

$('.load-user-company').select2({
    allowClear: true,
    dropdownAutoWidth : true,
    width: '100%',
    placeholder: function(params) {
        return {
            id: null,
            text: params.placeholder,
        }
    },
    ajax: {
        method: 'GET',
        url: base_url + '/load-ajax/loadUserCompany',
        dataType: 'json',
        data: function (params) {
            var query = {
                search: $.trim(params.term),
                page: params.page,
            };
            return query;
        }
    }
});

$(window).on('scroll', function () {
    if(!(/Android|webOS|iPhone|IEMobile|Opera Mini/i.test(navigator.userAgent))){
        if(window.scrollY > 0){
            $('#logo').removeClass('remove_pl240');
            $('.banner_frontend').hide();
            $('.sa4d25').addClass('mt_130');
            $('.infomation_of_user ').addClass('mt_130');
            $('.sa4d25').removeClass('mt_scroll');
            $('.infomation_of_user ').removeClass('mt_scroll');
            $('.button_wrapper_menu').hide('slow');
            $('.wrraped_button_vertical_menu_left_scroll').show();
        } else {
            $('.wrraped_button_vertical_menu_left_scroll').hide();
            $('.button_wrapper_menu').show();
            $('#logo').addClass('remove_pl240');
            $('.banner_frontend').show('slow');
            $('.sa4d25').removeClass('mt_130');
            $('.infomation_of_user ').removeClass('mt_130');
            $('.sa4d25').addClass('mt_scroll');
            $('.infomation_of_user ').addClass('mt_scroll');
        }
    } else {
        if(window.scrollY > 0){
            $('.banner_frontend').hide();
            $('.sa4d25').addClass('mt_130');
            $('.infomation_of_user ').addClass('mt_130');
            $('.sa4d25').removeClass('mt_scroll');
            $('.infomation_of_user ').removeClass('mt_scroll');
        } else {
            $('.banner_frontend').show('slow');
            $('.sa4d25').removeClass('mt_130');
            $('.infomation_of_user ').removeClass('mt_130');
            $('.sa4d25').addClass('mt_scroll');
            $('.infomation_of_user ').addClass('mt_scroll');
        }
    }
});

if (tabs != 'survey-react' && check_survey_popup){
    $('.modal-survey-popup').modal();

    function update_num_popup(survey_id){
        $.ajax({
            url: update_survey_popup,
            type: 'post',
            data: {
                survey_id: survey_id,
            }
        }).done(function(data) {
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }
}
