var show_list;
var sort_type = 'alphabetic';

$(document).ready(function () {
    bootbox.setDefaults({locale: lang['locale-bootbox']});
    loadFolders();
    performLfmRequest('errors')
        .done(function (data) {
            var response = JSON.parse(data);
            for (var i = 0; i < response.length; i++) {
                $('#alerts').append(
                    $('<div>').addClass('alert alert-warning')
                        .append($('<i>').addClass('fa fa-exclamation-circle'))
                        .append(' ' + response[i])
                );
            }
        });

    $(window).on('dragenter', function () {
        $('#uploadModal').modal('show');
    });
});

// ======================
// ==  Navbar actions  ==
// ======================

$('#nav-buttons a').click(function (e) {
    e.preventDefault();
});

$('#to-previous').click(function () {
    var previous_dir = getPreviousDir();
    if (previous_dir == '') return;
    goTo(previous_dir);
});

$('#add-folder').click(function () {
    /*bootbox.prompt(lang['message-name'], function (result) {
        if (result == null) return;
        createFolder(result);
    });*/
    $('#addFolderModal').modal('show');
});

$('#upload').click(function () {
    $('#uploadModal').modal('show');
});

$('#upload-btn').click(function () {
    $(this).html('')
        .append($('<i>').addClass('fa fa-refresh fa-spin'))
        .append(" " + lang['btn-uploading'])
        .addClass('disabled');

    function resetUploadForm() {
        $('#uploadModal').modal('hide');
        $('#upload-btn').html(lang['btn-upload']).removeClass('disabled');
        $('input#upload').val('');
    }

    $('#uploadForm').ajaxSubmit({
        success: function (data, statusText, xhr, $form) {
            resetUploadForm();
            refreshFoldersAndItems(data);
            displaySuccessMessage(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            displayErrorResponse(jqXHR);
            resetUploadForm();
        }
    });
});

$('#b-add-folder').on('click', function () {

    let name = $('#folder-name').val();
    let current_dir = $('#working_dir').val();
    let type = $('#type').val();

    if (name == '') {
        return false;
    }

    $.ajax({
        type: 'POST',
        dataType: 'text',
        url: lfm_route + '/newfolder',
        data: {
            '_token': _token,
            'name': name,
            'parent': current_dir,
            'type': type,
        },
        cache: false
    }).done(function(data) {
        if (data == 'OK') {
            $('#folder-name').val('').trigger('change');

            $('#addFolderModal').modal('hide');
            var success = $('<div>').addClass('alert alert-success')
                .append($('<i>').addClass('fa fa-check'))
                .append('Tạo thư mục thành công.');
            $('#alerts').append(success);
            setTimeout(function () {
                success.remove();
            }, 2000);
        }
    }, refreshFoldersAndItems).fail(function (jqXHR, textStatus, errorThrown) {
        displayErrorResponse(jqXHR);
    });
});

$('#thumbnail-display').click(function () {
    show_list = 0;
    loadItems();
});

$('#list-display').click(function () {
    show_list = 1;
    loadItems();
});

$('#list-sort-alphabetic').click(function () {
    sort_type = 'alphabetic';
    loadItems();
});

$('#list-sort-time').click(function () {
    sort_type = 'time';
    loadItems();
});

// ======================
// ==  Folder actions  ==
// ======================

$(document).on('click', '.file-item', function (e) {
    useFile($(this).data('id'));
});

$(document).on('click', '.folder-item', function (e) {
    goTo($(this).data('id'));
});

function goTo(new_dir) {
    $('#working_dir').val(new_dir);
    loadItems();
}

function getPreviousDir() {
    //var ds = '/';
    //var working_dir = $('#working_dir').val();
    //var last_ds = working_dir.lastIndexOf(ds);
    //var previous_dir = working_dir.substring(0, last_ds);

    return $('#previous_dir').val();
}

function dir_starts_with(str) {
    return $('#working_dir').val().indexOf(str) === 0;
}

function setOpenFolders() {
    var folders = $('.folder-item');
    var workingDir = $('#working_dir').val();
    for (var i = folders.length - 1; i >= 0; i--) {
        // close folders that are not parent
        if ($(folders[i]).data('id') != workingDir) {
            $(folders[i]).children('i').removeClass('fa-folder-open').addClass('fa-folder');
        } else {
            $(folders[i]).children('i').removeClass('fa-folder').addClass('fa-folder-open');
        }
    }
}

// ====================
// ==  Ajax actions  ==
// ====================

function performLfmRequest(url, parameter, type) {
    var data = defaultParameters();

    if (parameter != null) {
        $.each(parameter, function (key, value) {
            data[key] = value;
        });
    }
    return $.ajax({
        type: 'GET',
        dataType: type || 'text',
        url: lfm_route + '/' + url,
        data: data,
        cache: false
    }).fail(function (jqXHR, textStatus, errorThrown) {
        displayErrorResponse(jqXHR);
    });
}

function displayErrorResponse(jqXHR) {
    notify('<div style="max-height:50vh;overflow: scroll;">' + jqXHR.responseText + '</div>');
}

function displaySuccessMessage(data) {
    if (data == 'OK') {
        var success = $('<div>').addClass('alert alert-success')
            .append($('<i>').addClass('fa fa-check'))
            .append('Upload file thành công.');
        $('#alerts').append(success);
        setTimeout(function () {
            success.remove();
        }, 2000);
    }
}

var refreshFoldersAndItems = function (data) {
    loadFolders();
    if (data != 'OK') {
        data = Array.isArray(data) ? data.join('<br/>') : data;
        notify(data);
    }
};

var hideNavAndShowEditor = function (data) {
    $('#nav-buttons > ul').addClass('hidden');
    $('#content').html(data);
};

function loadFolders() {
    performLfmRequest('folders', {}, 'html')
        .done(function (data) {
            $('#tree').html(data);
            loadItems();
        });
}

function loadItems() {
    $('#lfm-loader').show();
    performLfmRequest('json-items', {
        show_list: show_list,
        sort_type: sort_type
    }, 'html')
        .done(function (data) {
            var response = JSON.parse(data);
            $('#content').html(response.html);
            $('#nav-buttons > ul').removeClass('hidden');
            $('#working_dir').val(response.working_dir);
            $('#current_dir').text(response.working_dir);
            $('#previous_dir').val(response.previous_dir);
            //console.log('Current working_dir : ' + $('#working_dir').val());
            // console.log($('#previous_dir').val());
            if (getPreviousDir() < 0) {
                $('#to-previous').addClass('d-none');
            } else {
                $('#to-previous').removeClass('d-none');
            }
            setOpenFolders();
        })
        .always(function () {
            $('#lfm-loader').hide();
        });
}

function createFolder(folder_name) {
    performLfmRequest('newfolder', {name: folder_name}).done(refreshFoldersAndItems);
}

function rename(item_id,item_name,type) {
    bootbox.prompt({
        title: lang['message-rename'],
        value: item_name,
        callback: function (result) {
            if (result == null) return;
            performLfmRequest('rename', {
                id: item_id,
                file: item_name,
                new_name: result,
                type:type
            }).done(refreshFoldersAndItems);
        }
    });
}

function trash(item_id, item_name, is_file, type_item, message = null) {
    var checkMessage = message ? message : lang['message-delete'];
    var acceptDelete = message ? 1 : 0;
    bootbox.confirm(checkMessage, function (result) {
        if (result == true) {
             //performLfmRequest('delete', {items: item_name}).done(refreshFoldersAndItems);
            $.ajax({
                type: 'GET',
                dataType: 'text',
                url: lfm_route + '/delete',
                data: {
                    'name': item_name,
                    'id': item_id,
                    'is_file': is_file,
                    'acceptDelete': acceptDelete,
                    'type' : type_item,
                },
                cache: false
            }).done(function(data) {
                console.log(data);
                if (data == 'FALSE') {
                    trash(item_id, item_name, is_file, 'File đang được sử dụng bạn có chắc muốn xóa');
                } else if(data == 'OK') {
                    var success = $('<div>').addClass('alert alert-success')
                        .append($('<i>').addClass('fa fa-check'))
                        .append('Xoá thành công.');
                    $('#alerts').append(success);
                    setTimeout(function () {
                        success.remove();
                    }, 2000);
                } 
            }, refreshFoldersAndItems);
        }
    });
}

function cropImage(image_name) {
    performLfmRequest('crop', {img: image_name})
        .done(hideNavAndShowEditor);
}

function resizeImage(image_name) {
    performLfmRequest('resize', {img: image_name})
        .done(hideNavAndShowEditor);
}

function download(file_name) {
    var data = defaultParameters();
    data['file'] = file_name;
    location.href = lfm_route + '/download?' + $.param(data);
}

// ==================================
// ==  Ckeditor, Bootbox, preview  ==
// ==================================

function useFile(file_url, name = "") {

    function getUrlParam(paramName) {
        var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
        var match = window.location.search.match(reParam);
        return (match && match.length > 1) ? match[1] : null;
    }

    function useTinymce3(url) {
        var win = tinyMCEPopup.getWindowArg("window");
        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = url;
        if (typeof (win.ImageDialog) != "undefined") {
            // Update image dimensions
            if (win.ImageDialog.getImageData) {
                win.ImageDialog.getImageData();
            }

            // Preview if necessary
            if (win.ImageDialog.showPreviewImage) {
                win.ImageDialog.showPreviewImage(url);
            }
        }
        tinyMCEPopup.close();
    }

    function useTinymce4AndColorbox(url, field_name) {
        parent.document.getElementById(field_name).value = url;

        if (typeof parent.tinyMCE !== "undefined") {
            parent.tinyMCE.activeEditor.windowManager.close();
        }
        if (typeof parent.$.fn.colorbox !== "undefined") {
            parent.$.fn.colorbox.close();
        }
    }

    function useCkeditor3(url) {
        if (window.opener) {
            // Popup
            window.opener.CKEDITOR.tools.callFunction(getUrlParam('CKEditorFuncNum'), url);
        }
        else {
            // Modal (in iframe)
            parent.CKEDITOR.tools.callFunction(getUrlParam('CKEditorFuncNum'), url);
            parent.CKEDITOR.tools.callFunction(getUrlParam('CKEditorCleanUpFuncNum'));
        }
    }

    function useFckeditor2(url) {
        var p = url;
        var w = data['Properties']['Width'];
        var h = data['Properties']['Height'];
        window.opener.SetUrl(p, w, h);
    }

    var url = file_url;
    var field_name = getUrlParam('field_name');
    var is_ckeditor = getUrlParam('CKEditor');
    var is_fcke = typeof data != 'undefined' && data['Properties']['Width'] != '';
    var file_path = url.replace(route_prefix, '');

    if (window.opener || window.tinyMCEPopup || field_name || getUrlParam('CKEditorCleanUpFuncNum') || is_ckeditor) {
        if (window.tinyMCEPopup) { // use TinyMCE > 3.0 integration method
            useTinymce3(url);
        }
        else if (field_name) {   // tinymce 4 and colorbox
            useTinymce4AndColorbox(url, field_name);
        }
        else if (is_ckeditor) {   // use CKEditor 3.0 + integration method
            useCkeditor3(url);
        }
        else if (is_fcke) {      // use FCKEditor 2.0 integration method
            useFckeditor2(url);
        }
        else {                   // standalone button or other situations
            window.opener.SetUrl(url, file_path, name);
        }

        if (window.opener) {
            window.close();
        }
    }
    else {
        // No editor found, open/download file using browser's default method
        window.open(url);
    }
}

//end useFile

function defaultParameters() {
    return {
        working_dir: $('#working_dir').val(),
        type: $('#type').val()
    };
}

function notImp() {
    notify('Not yet implemented!');
}

function notify(message) {
    bootbox.alert(message);
}

function fileView(file_url, timestamp) {
    bootbox.dialog({
        title: lang['title-view'],
        message: $('<img>')
            .addClass('img img-responsive center-block')
            .attr('src', file_url + '?timestamp=' + timestamp),
        size: 'large',
        onEscape: true,
        backdrop: true
    });
}
