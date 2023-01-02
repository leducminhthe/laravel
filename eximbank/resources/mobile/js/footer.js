$(document).ready(function() {
    setTimeout(function(){
        $(".show_note_mobile").show('slow');
    }, 1000);
});
function noteMenu(){
    $('#modal-create-note-mobile').modal();
}
var clicks = 0;
function addNewNote() {
    clicks += 1;
    $('#modal-body-note').append(`<div class="form-group row" id="date_time_`+clicks+`">
                                    <div class="col-md-3 label">
                                    </div>
                                    <div class="col-md-9">
                                        <input type="datetime-local" id="date_time" name="date_times[]">
                                    </div>
                                </div>
                                <div class="form-group row" id="content_`+clicks+`">
                                    <div class="col-md-3 label">
                                    </div>
                                    <div class="col-md-7">
                                        <textarea class="form-control" name="contents[]" required></textarea>
                                    </div>
                                    <div class="col-sm-2 control-label">
                                        <button type="button" onclick="closeAddNewNote(`+clicks+`)" class="btn text-white">{{ trans('labutton.delete') }}</button>
                                    </div>
                                </div>`)
}
function closeAddNewNote(id) {
    $('#date_time_'+id).remove();
    $('#content_'+id).remove();
}
function closeShowNote(id) {
    $('#note_id_'+id).remove();
    $.ajax({
        type: 'POST',
        url: $('#element_app_mobile').attr('data-url_close_note'),
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
$(window).on('load',function() {
    // Animate loader off screen
    $("#loader").fadeOut("slow");;
});
