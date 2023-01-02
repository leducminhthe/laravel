var processing = $('#languages_menu_bottom').attr("data-processing")
var notice_time = $('#languages_menu_bottom').attr("data-notice_time")
var content_note = $('#languages_menu_bottom').attr("data-content_note")
var deleted = $('#languages_menu_bottom').attr("data-delete")

$('.btn_to_top').hide();
function topFunction() {
    $('html,body').animate({ scrollTop: 0 }, 'slow');
    return false;
}

if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
    $("#show_menu").click(function(){
        if ($('.all_item_menu').hasClass('active')) {
            $('.all_item_menu').hide('slow');
            $('.all_item_menu').removeClass('active');
        } else {
            $('.all_item_menu').show('slow');
            $('.all_item_menu').addClass('active');
        }
    });
}

function change(name){
    $('.'+ name).find('.title_item').show();
}
function back(name){
    $('.'+ name).find('.title_item').hide();
}

function dateTime(e) {
    e.preventDefault();
}
$('#create_suggest').on('click', function() {
    $('#modal-create').modal();
});

$('#note_menu').on('click', function() {
    $('#modal-create-note').modal();
});

var clicks = 0;
function addNewNote() {
    clicks += 1;
    $('#modal-body-note').append(`<div class="form-group row" id="date_time_`+clicks+`">
                                <div class="col-md-3 label">
                                    <label>`+ notice_time +`</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="datetime-local" id="date_time" name="date_times[]" onkeydown="dateTime(e)">
                                </div>
                            </div>
                            <div class="form-group row" id="content_`+clicks+`">
                                <div class="col-md-3 label">
                                    <label>`+ content_note +`</label>
                                </div>
                                <div class="col-md-7">
                                    <textarea class="form-control" name="contents[]" required></textarea>
                                </div>
                                <div class="col-sm-2 control-label">
                                    <button type="button" onclick="closeAddNewNote(`+clicks+`)" class="btn">`+ deleted +`</button>
                                </div>
                            </div>`)
}
function closeAddNewNote(id) {
    $('#date_time_'+id).remove();
    $('#content_'+id).remove();
}
function closeShowNote(id) {
    var closeNote = $('#close_note').val()
    $('#note_id_'+id).remove();
    $.ajax({
        type: 'POST',
        url: closeNote,
        data: {
            id: id,
        }
    }).done(function(data) {
        return false;
    }).fail(function(data) {
        show_message('Lỗi dữ liệu', 'error');
        return false;
    });
}
$('#my_audio').css('display','none')
function togglePlay() {
    var pause = document.querySelector(".pause");
    var audio = document.querySelector(".audio");
    audio.play();
}

function saveSuggest(event) {
    var saveSuggest = $('#save_suggest').val()
    let item = $('.save_suggest');
    let oldtext = item.html();
    item.html('<i class="fa fa-spinner fa-spin"></i> ' + processing);
    $('.save_suggest').attr('disabled',true);

    var name =  $("input[name=name]").val();
    var content =  $(".content_suggest").val();

    event.preventDefault();
    $.ajax({
        url: saveSuggest,
        type: 'post',
        data: {
            'name': name,
            'content': content,
        }
    }).done(function(data) {
        item.html(oldtext);
        $('.save_suggest').attr('disabled',false);
        if (data && data.status == 'success') {
            show_message(data.message, data.status);
            window.location.href = data.redirect
        } else {
            show_message(data.message, data.status);
        }
        return false;
    }).fail(function(data) {
        show_message('Lỗi dữ liệu', 'error');
        return false;
    });
}