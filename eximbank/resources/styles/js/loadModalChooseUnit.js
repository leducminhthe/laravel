var empty = 0;
var userUnit = $('.element_data').attr("data-user_unit")
var multiple = $('#multiple_unit').val()
function chooseUnitHandle(userUnit) {
    let item = $('.wrraped_unit_choose');
    let oldtext = item.html();
    var url_choose_unit_modal = $('.element_data').attr("data-url_choose_unit_modal")
    var unitIdSelected = $('#search_unit_id').val()
    if(unitIdSelected && ((multiple != 1 && unitIdSelected != userUnit) || (multiple == 1 && !unitIdSelected.split(',').includes(userUnit)))) {
        if(multiple != 1) {
            userUnit = unitIdSelected;
        } else {
            var arr = unitIdSelected.split(',')
            userUnit = Math.max.apply(Math, arr)
        }
        var type = 3;
    } else {
        userUnit = userUnit;
        var type = 1;
    }
    item.html('<div class="w-100 text-center"><i class="fa fa-spinner fa-spin"></i></div>');
    document.querySelector('.wrraped_unit_choose').style.pointerEvents = 'none';
    $("#app-modal").html('');
    $.ajax({
        url: url_choose_unit_modal,
        type: 'post',
        dataType: 'html',
        data: {
            multiple: multiple
        }
    }).done(function(data) {
        item.html(oldtext);
        document.querySelector('.wrraped_unit_choose').style.pointerEvents = 'auto';
        $("#app-modal").html(data);
        var page = $('.page').val();
        if(multiple == 1 && $('.name_unit').find('.title_check_unit').length !== 0) {
            $('.wrapped_checkbox_unit').show();
            let html = $('.name_unit').html();
            $('.wrapped_checkbox_unit').html(html)
        }
        load_choose_unit(page, userUnit, type, 0);
        $("#app-modal #modal-choose-unit").modal();
    }).fail(function(data) {
        show_message('Lỗi hệ thống', 'error');
        return false;
    });
}

function load_choose_unit(page, userUnit, type, loadMore){
    var value_check_unit = $(".wrapped_checkbox_unit .unit_check_id").map(function(){return $(this).val();}).get();
    var unitIdSelected = value_check_unit.toString();
    var url_load_unit_modal = $('.element_data').attr("data-url_load_unit_modal")
    if (loadMore != 1) {
        $("#results_unit").html('');
        $('.page').val(1);
        empty = 0;
    } 
    $.ajax({
        url: url_load_unit_modal + "?page=" + page + "&userUnit=" + userUnit + "&type=" + type + "&multiple=" + multiple + "&unitIdSelected=" + unitIdSelected,
        type: "get",
        datatype: "html",
        beforeSend: function() {
            $('.ajax-loading').show();
        }
        })
        .done(function(data) {
            if(data[0].length == 0){
            empty = 1;
            $('.ajax-loading').hide();
            return;
        }
        $('.ajax-loading').hide();
        $("#results_unit").append(data[0]);
        $('.level').val(data[2]);
        console.log(data[1], data[2], data[3], type);
        $('.old_unit').val(data[1]);
        if(type != 1 && data[2] > 1) {
            $('.back').show();
            $('.modal_title_load_unit').html(data[3]);
        } else {
            $('.modal_title_load_unit').html('Đơn vị');
            $('.back').hide();
        }
    })
    .fail(function(jqXHR, ajaxOptions, thrownError) {
        alert('No response from server');
    });
}

function backUnitHandle(){
    var oldUnit = $('.old_unit').val();
    var type = '';
    var unitId = '';
    if(userUnit == oldUnit) {
        type = 1;
        unitId = userUnit;
    } else {
        type = 3;
        unitId = oldUnit;
    }
    load_choose_unit(1, unitId, type, 0)
}

function selectUnit(id) {
    $('#search_unit_id-error').html('')
    var nameUnit = $('.unit_name_'+ id).val()
    $('.name_unit').html('<div class="get_name_unit">'+ nameUnit +'</div>')
    $('.unit_id').val(id)
    $("#modal-choose-unit").modal('toggle');
    $('.delete_unit_id').show();
}

function deleteUnitSearch() {
    $('#search_unit_id').val('')
    $('.name_unit').html('<div class="default_title">'+ $('.element_data').attr("data-choose_unit") +'</div>')
    $('.delete_unit_id').hide();
}

function checkBoxUnitHandle(unitId) {
    var name_unit = $('.unit_name_'+ unitId).val()
    var html = `<span class="title_check_unit" id="title_unit_check_`+ unitId +`">
                    `+ name_unit +`
                    <span class="remove_check" id="remove_check_unit_`+ unitId +`" onclick="removeCheckUnitHandle(`+ unitId +`)"> x</span>
                    <input type="hidden" name="unit_check_id[]" class="unit_check_id" value="`+ unitId +`"/>
                </span>`
    if($('#checkbox_unit_' + unitId).is(":checked")) {
        $('.wrapped_checkbox_unit').show();
        $('.wrapped_checkbox_unit').find(".title_checkbox").remove()
        $('.wrapped_checkbox_unit').append(html)
    } else {
        $('.wrapped_checkbox_unit').find("#title_unit_check_"+ unitId).remove()
        if ($('.wrapped_checkbox_unit').children().length == 0) {
            $('.wrapped_checkbox_unit').hide();
            // $('.wrapped_checkbox_unit').html('<span class="title_checkbox ml-2">Chọn đơn vị</span>')
        }
    }
}

function removeCheckUnitHandle(unitId) {
    $('.wrapped_checkbox_unit').find("#title_unit_check_"+ unitId).remove()
    if ($('.wrapped_checkbox_unit').children().length == 0) {
        $('.wrapped_checkbox_unit').hide();
        // $('.wrapped_checkbox_unit').html('<span class="title_checkbox ml-2">Chọn đơn vị</span>')
    }
    $('#checkbox_unit_'+ unitId).prop('checked', false)
}

function selectMultipleUnitHandle() {
    var value_check_unit = $(".wrapped_checkbox_unit .unit_check_id").map(function(){return $(this).val();}).get();
    var ids = value_check_unit.toString();
    $('#search_unit_id').val(ids)
    var html = $('.wrapped_checkbox_unit').html();
    $('.name_unit').html(html)
    $("#modal-choose-unit").modal('toggle');
}