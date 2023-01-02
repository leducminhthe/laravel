if ($('.menu_left_frontend').hasClass('w_50')) {
    $('#btn_search_user').hide();
    $('.total_time_learn').hide();
}

var tabs = $('#tabs').val();
if (tabs && tabs != 'user' && tabs != 'news-react' && tabs != 'all-course') {
    $('.'+tabs).addClass('active_menu_child');
}

if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
    $('.menu_child').css('background', 'aliceblue');
    $('.library').click(function(){
        if ($(this).hasClass('active_sub_menu_child')) {
            $(this).removeClass('active_sub_menu_child');
            $(this).css('height', '35px');
            $(this).find('.sub_menu_child').removeClass('show_menu_schild_responsive');
        } else {
            $(this).addClass('active_sub_menu_child');
            $(this).css('height', 'unset')
            $(this).find('.sub_menu_child').addClass('show_menu_schild_responsive');
        }
    })
    $('.course_online').click(function(){
        if ($(this).hasClass('active_sub_menu_child')) {
            $(this).removeClass('active_sub_menu_child');
            $(this).css('height', '35px');
            $(this).find('.sub_menu_child').removeClass('show_menu_schild_responsive');
        } else {
            $(this).addClass('active_sub_menu_child');
            $(this).css('height', 'unset')
            $(this).find('.sub_menu_child').addClass('show_menu_schild_responsive');
        }
    })
    $('.guide-react').click(function(){
        if ($(this).hasClass('active_sub_menu_child')) {
            $(this).removeClass('active_sub_menu_child');
            $('.library').css('height', '35px');
            $(this).find('.sub_menu_child').removeClass('show_menu_schild_responsive');
        } else {
            $(this).addClass('active_sub_menu_child');
            $(this).css('height', 'unset')
            $(this).find('.sub_menu_child').addClass('show_menu_schild_responsive');
        }
    })

    $('.wrraped_button_vertical_menu_left_scroll').on('click', function(){
        if ($('.menu_left_frontend').hasClass('display_menu_left_frontend')) {
            $('.menu_left_frontend').removeClass('display_menu_left_frontend');
            $(this).find('i').removeClass('uil uil-bars');
            $(this).find('i').addClass('uil uil-times');
        }else{
            $('.menu_left_frontend').addClass('display_menu_left_frontend');
            $(this).find('i').removeClass('uil uil-times');
            $(this).find('i').addClass('uil uil-bars');
        }
    });
} else {
    var menu_clicked = localStorage.getItem("menu_click") ? JSON.parse(localStorage.getItem("menu_click")) : '';
    if (menu_clicked && !$('.menu_left_frontend').hasClass('w_50')) {
        $('#'+menu_clicked).addClass('active_menu_item');
        $('.child_'+menu_clicked).show();
        $('.active_menu_item').find('.menu--link').addClass('active_menu_color');
        $('#icon_parent_'+menu_clicked).removeClass('fa-chevron-down');
        $('#icon_parent_'+menu_clicked).addClass('fa-chevron-up');
    }
}

function hoverSubmenu(id) {
    if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
        $('#'+ id).find('.sub_menu_child').show();
        $('.all_item_menu_frontend').addClass('w_500');
    }
}
function outHover(id) {
    if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
        $('#'+ id).find('.sub_menu_child').hide();
        if (!$('.menu_left_frontend').hasClass('w_50')) {
            $('.all_item_menu_frontend').removeClass('w_500');
        }
    }
}

function showMenuChild(name) {
    if ($('.menu_left_frontend').hasClass('w_50')) {
        $('.menu_child').removeClass('show_menu_child');
        $('.menu_child').hide();
        $('.menu--item').find('label').css('background', 'unset');
        $('.menu--item').removeClass('active_menu');

        $('.all_item_menu_frontend').addClass('w_500');
        $('.child_'+name).addClass('show_menu_child');
        $('.child_'+name).show('slow');
        $('#'+name).find('label').css('background', get_hover_color);
        $('#'+name).addClass('active_menu');
    } else {
        if ($('#'+name).hasClass('active_menu_item')) {
            $('#'+name).removeClass('active_menu_item');
            if(!$('#'+name).find('.menu--link').hasClass('have_child_active')) {
                $('#'+name).find('.menu--link').removeClass('active_menu_color');
            } 
            $('.child_'+name).hide('slow');
            if(!(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))){
                localStorage.setItem("menu_click", JSON.stringify(''));
            }
        } else {
            $('.active_menu_item').find('.menu--link').removeClass('active_menu_color');
            $('.menu--item').removeClass('active_menu_item')
            $('.menu_child').hide('slow');

            $('#'+name).addClass('active_menu_item');
            $('.active_menu_item').find('.menu--link').addClass('active_menu_color');
            $('.child_'+name).show('slow');
            if(!(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))){
                localStorage.setItem("menu_click", JSON.stringify(name));
            }
        }
        $('.all_item_menu_frontend').removeClass('w_500');
    }

    if($('#icon_parent_'+name).hasClass('fa-chevron-down')){
        $('#icon_parent_'+name).removeClass('fa-chevron-down');
        $('#icon_parent_'+name).addClass('fa-chevron-up');
    }else{
        $('#icon_parent_'+name).removeClass('fa-chevron-up');
        $('#icon_parent_'+name).addClass('fa-chevron-down');
    }

}

$(document).ready(function(){
    $(".btn_vertical_menu_frontend").click(function(){
        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
            if ($('.menu_left_frontend .wrapped_menu').hasClass('display_show')) {
                $('.menu_left_frontend .wrapped_menu').removeClass('display_show');
            } else {
                $('.menu_left_frontend .wrapped_menu').addClass('display_show');
            }
            $('.search_user').hide();
            $('.total_time_learn').hide();
        } else {
            $('.menu_child').removeClass('show_menu_child');
            $('.menu_child').hide();
            if ($('.menu_left_frontend').hasClass('w_50')) {
                $('.menu_left_frontend').removeClass('w_50');
                $('.menu_left_frontend').addClass('w_240');
                $('.body_frontend').removeClass('wrapper__minify');
                $('.tilte_item_menu').show();
                $('.search_user').show();
                $('.menu--item').removeClass('w_50');
                open_close_menu(1);
                $('.total_time_learn').show();
            } else {
                $('.menu_left_frontend').addClass('w_50');
                $('.menu_left_frontend').removeClass('w_240');
                $('.body_frontend').addClass('wrapper__minify');
                $('.tilte_item_menu').hide();
                $('.search_user').hide();
                $('.menu--item').addClass('w_50');
                open_close_menu(0);
                $('.total_time_learn').hide();
            }
        }
    });

    $( "body" ).click(function( event ) {
        if (event.target.className != "menu--icon icon_menu_parent" && $('.menu_left_frontend').hasClass('w_50')) {
            $('.all_item_menu_frontend').removeClass('w_500');
            $('.menu_child').removeClass('show_menu_child');
            $('.menu_child').hide();
            $('.menu--item').find('label').css('background', 'unset');
            $('.menu--item').removeClass('active_menu');
        }
    });
});

$("#btn_search_user").on('click', function(){
    var route_search = $('#search').val();
    if($(this).hasClass('show_search_user')){
        $('.form_search_user').show();
        $(this).removeClass('show_search_user');
    }else{
        var search = $('input[name=search_user]').val();
        if(search){
            $.ajax({
                type: "POST",
                url: route_search,
                data:{
                    search: search
                },
                success: function (data) {
                    if (data) {
                        console.log(data);

                        if(data.redirect){
                            window.location = data.redirect;
                        }else{
                            show_message('Không tồn tại nhân viên', 'warning');
                        }

                        return false;
                    } else {
                        console.log('Lỗi hệ thống');

                        return false;
                    }
                }
            });
        }else{
            $('.form_search_user').hide();
            $(this).addClass('show_search_user');
        }
    }
});

function open_close_menu(status) {
    var close = $('#close_open').val();
    $.ajax({
        url: close,
        type: 'post',
        data: {
            status: status,
        }
    }).done(function(data) {
        return false;
    }).fail(function(data) {
        show_message('Lỗi dữ liệu', 'error');
        return false;
    });
}

var test = $('.active_menu_child').parent().attr('id')
var get_id = $('#'+test).data('id')
$('#menu_'+ get_id).find('.menu--link').addClass('active_menu_color have_child_active');
if (tabs == 'news-react') {
    $('#menu_news').addClass('active_menu_color');
}

function kpiTimeLearn() {
    var kpi_template = $('#kpi_template').val();
    $.ajax({
        url: kpi_template,
        type: 'post',
    }).done(function(data) {
        return false;
    }).fail(function(data) {
        show_message('Lỗi dữ liệu', 'error');
        return false;
    });
}