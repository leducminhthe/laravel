// LOAD DỮ LIỆU
var course_type = $('.element_data').attr("data-course_type");
var last_page = '';
var trainingProgram = $('.element_data').attr("data-training_program");
var listType = $('.element_data').attr("data-list_type");
var page = 1;
var empty = 0;
load_more(page);

if (course_type != 3) {
  $(window).scroll(function () {
    if ($(window).scrollTop() + $(window).height() >= $(document).height() - 10) {
      console.log(1);
      page++;

      if (page <= last_page) {
        load_more(page);
      }
    }
  });
}

function moreCourse() {
  page += 1;
  load_more(page);
}

function load_more(page) {
  var urlAllCourse = $('.element_data').attr("data-url_all_course");
  $.ajax({
    url: urlAllCourse + "?trainingProgramId=" + trainingProgram + "&page=" + page + "&listType=" + listType,
    type: "get",
    datatype: "html",
    beforeSend: function beforeSend() {
      $('.ajax-loading').show();
    }
  }).done(function (data) {
    if (data[0].length == 0) {
      empty = 1;
      $('.ajax-loading').hide();
      $('.more_course').hide();
      return;
    }

    $('.ajax-loading').hide();
    $('.wrraped_related_course').show();
    $("#results").append(data[0]);
    last_page = data[1];

    if (data[0].length > 0 && course_type == 3 && data[1] > 1) {
      $('.more_course').show();
    }

    filterBasic();
  }).fail(function (jqXHR, ajaxOptions, thrownError) {
    alert('No response from server');
  });
} /////
// TÌM KIẾM


var search_course_type = [];
var search_status = [];
var fromdate = '';
var todate = '';
var search = '';
var training_program_id = [];
var level_subject_id = [];
$('.search_course_type').on('click', function () {
  search_course_type = $("input[name=search_course_type]:checked").map(function () {
    return $(this).val();
  }).get();
  ajaxSearchCourse(search_course_type, search_status, training_program_id, level_subject_id, fromdate, todate, search);
});
$('.search_status').on('click', function () {
  search_status = $("input[name=status_course]:checked").map(function () {
    return $(this).val();
  }).get();
  ajaxSearchCourse(search_course_type, search_status, training_program_id, level_subject_id, fromdate, todate, search);
});
$('.training_program_checkbox').on('click', function () {
  training_program_id = $("input[name=training_program_id]:checked").map(function () {
    return $(this).val();
  }).get();
  ajaxSearchCourse(search_course_type, search_status, training_program_id, level_subject_id, fromdate, todate, search);
});
$('.level_subject_checkbox').on('click', function () {
  level_subject_id = $("input[name=level_subject_id]:checked").map(function () {
    return $(this).val();
  }).get();
  ajaxSearchCourse(search_course_type, search_status, training_program_id, level_subject_id, fromdate, todate, search);
});
$('#fromdate').on('blur', function () {
  fromdate = $('#fromdate').val();
  ajaxSearchCourse(search_course_type, search_status, training_program_id, level_subject_id, fromdate, todate, search);
});
$('#todate').on('blur', function () {
  todate = $('#todate').val();
  ajaxSearchCourse(search_course_type, search_status, training_program_id, level_subject_id, fromdate, todate, search);
});
$("#search_course").on('blur', function (e) {
  search = $(this).val();
  ajaxSearchCourse(search_course_type, search_status, training_program_id, level_subject_id, fromdate, todate, search);
});

function ajaxSearchCourse(search_course_type, search_status, training_program_id, level_subject_id, fromdate, todate, search) {
  $("#results").html('');
  $('.ajax-loading').show();
  $.ajax({
    type: "POST",
    url: $('.element_data').attr("data-ajax_course_training_program"),
    data: {
      search_course_type: search_course_type,
      search_status: search_status,
      training_program_id: training_program_id,
      level_subject_id: level_subject_id,
      fromdate: fromdate,
      todate: todate,
      search: search,
      trainingProgramId: trainingProgram,
      course_type: course_type,
      listType: listType
    },
    success: function success(data) {
      $('.ajax-loading').hide();

      if (data[0].length > 0) {
        page = 1;
        last_page = data[1];
        $("#results").html(data[0]);
        var filter = $('input[name=filter_show_form]:checked').val();

        if (filter == 2) {
          filterDetail();
        } else if (filter == 3) {
          filterProgress();
        } else {
          filterBasic();
        }
      } else {
        $('#results').html("<div class=\"text-center col-12 m-5\">\n                                            <span>" + $('.element_data').attr("data-not_found") + "</span>\n                                        </div>");
      }
    }
  });
} ///////////////////////


if (course_type == 3) {
  var result_formatter_roadmap = function result_formatter_roadmap(value, row, index) {
    if (row.result == 1) {
      return '<i class="fa fa-check text-success"></i>';
    }

    return '<i class="fa fa-times text-danger"></i>';
  };

  // CHI TIẾT LỘ TRÌNH ĐÀO TẠO
  var detailTrainingByTitle = function detailTrainingByTitle() {
    $('#modal-training-by-title').modal();
  }; //CHI TIẾT LỘ TRÌNH NGHỀ NGHIỆP


  var detailCareerRoadmap = function detailCareerRoadmap() {
    $('#modal-career-roadmap').modal();
  };

  var index_formatter = function index_formatter(value, row, index) {
    return index + 1;
  };

  var result_formatter = function result_formatter(value, row, index) {
    if (row.result == 1) {
      return '<i class="fa fa-check text-success"></i>';
    }

    return '<i class="fa fa-times text-danger"></i>';
  };

  // CHI TIẾT LỘ TRÌNH HỌC TẬP
  var detailRoadmapCourse = function detailRoadmapCourse() {
    $('#modal-roadmap-course').modal();
  }; ///////////


  var openedClass = 'uil-minus uil';
  var closedClass = 'uil uil-plus';
  $('#tree-unit-training').on('click', '.tree-item-level-subject', function (e) {
    var id = $(this).data('id');
    var child_url = $(this).data('route');

    if ($(this).closest('.item').find('i:first').hasClass(openedClass)) {
      $('#list' + id).find('ul').remove();
    } else {
      $('#list' + id).load(child_url);
    }

    var icon = $(this).closest('.item').children('i:first');
    icon.toggleClass(openedClass + " " + closedClass);
  });
  var table_roadmap = new LoadBootstrapTable({
    locale: $('.element_data').attr("data-locale"),
    url: $('.element_data').attr("data-url_career_roadmap"),
    table: '#table-roadmap'
  });
  var table = new LoadBootstrapTable({
    locale: $('.element_data').attr("data-locale"),
    url: $('.element_data').attr("data-url_career_roadmap_0"),
    table: '#table_course_modal'
  });
  $('#tree-unit').on('click', '.view-career', function () {
    var title_id = $(this).data('id');
    var title_name = $(this).data('name');
    var new_url = "/career-roadmap/get-course/" + title_id;
    table.refresh({
      'url': new_url
    });
    $('.title-name').html(title_name);
    $('.td-title-name').html('<div class="th-inner ">' + title_name + '</div><div class="fht-cell"></div>');
    $('#course-modal').modal();
  });
  $('#tree-unit').on('click', '.tree-item', function (e) {
    var id = $(this).data('id');
    var type = $(this).data('type');

    if ($(this).find('i:first').hasClass(openedClass)) {
      $('#list' + id).find('ul').remove();
    } else {
      $.ajax({
        type: 'POST',
        url: $('.element_data').attr("data-url_career_roadmap_tree_folder"),
        dataType: 'json',
        data: {
          id: id,
          type: type
        }
      }).done(function (data) {
        var rhtml = '';
        var rhtml_user = '';
        rhtml += '<ul>';
        $.each(data.childs, function (i, item) {
          if (type == 2) {
            rhtml_user += '<a href="javascript:void(0)" class="btn edit-roadmap-title mr-1" data-id="' + item.id + '">' + '<i class="fa fa-edit"></i> ' + $('.element_data').attr("data-edit") + '</a>' + '<a href="javascript:void(0)" class="btn delete-roadmap-title mr-1" data-id="' + item.id + '">' + '<i class="fa fa-trash"></i> ' + $('.element_data').attr("data-delete") + '</a>';
          }

          rhtml += '<li>';
          rhtml += '<div class="row item mt-2">';
          rhtml += '<div class="col-md-8">';
          rhtml += '<a href="javascript:void(0)" data-id="' + item.id + '" data-type="' + type + '" class="tree-item"> <i class="uil uil-plus"></i>' + item.title_name + '</a>';
          rhtml += '<span class="seniority_careers_roadmap">Thâm niên (năm): ' + item.seniority + ' </span>';
          rhtml += '</div>';
          rhtml += '<div class="col-md-4 text-right pr-0">';
          rhtml += rhtml_user;
          rhtml += '<a href="javascript:void(0)" class="btn view-career" data-id="' + item.title_id + '" data-name="' + item.title_name + '">';
          rhtml += '<i class="fa fa-eye"></i> ' + $('.element_data').attr("data-view");
          rhtml += '</a>';
          rhtml += '</div>';
          rhtml += '</div>';
          rhtml += '<div class="row">' + '<div class="col-md-12 pr-0" id="list' + item.id + '"></div>' + '</div>';
          rhtml += '</li>';
        });
        rhtml += '</ul>';
        document.getElementById('list' + id).innerHTML = '';
        document.getElementById('list' + id).innerHTML = rhtml;
      }).fail(function (data) {
        show_message('Lỗi hệ thống', 'error');
        return false;
      });
    }

    if (this == e.target) {
      var icon = $(this).children('i:first');
      icon.toggleClass(openedClass + " " + closedClass);
      $(this).children().children().toggle();
    }
  });
}

function openSearch() {
  $('.all_search').toggle('slow');
}

function showTrainingProgram() {
  $('#modal-training-program').modal();
}

function showLevelSubject() {
  $('#modal-level-subject').modal();
} // Điểm thưởng


function stt_formatter_bonus(value, row, index) {
  return index + 1;
} //MODAL ĐIỂM THƯỞNG


function openModalBonus(id, type) {
  $.ajax({
    type: "POST",
    url: $('.element_data').attr("data-url_ajax_bonus_course"),
    data: {
      id: id,
      type: type
    },
    success: function success(data) {
      $('#checkbox_promotion').html(data.html);
      $('#promotion_description').html(data.rhtml);

      if (data.landmarks !== '' && data.other == '' && data.complete == '') {
        $(".promotion_0_group_" + id + "_" + type + "").hide();
        $(".promotion_1_group_" + id + "_" + type + "").show();
        $(".promotion_2_group_" + id + "_" + type + "").hide();
      } else if (data.landmarks == '' && data.other !== '' && data.complete == '') {
        $(".promotion_0_group_" + id + "_" + type + "").hide();
        $(".promotion_1_group_" + id + "_" + type + "").hide();
        $(".promotion_2_group_" + id + "_" + type + "").show();
      } else {
        $(".promotion_0_group_" + id + "_" + type + "").show();
        $(".promotion_1_group_" + id + "_" + type + "").hide();
        $(".promotion_2_group_" + id + "_" + type + "").hide();
      }

      $('#modal-bonus').modal();
    }
  });
} //KTRA ĐIỂM THƯỞNG


function checkBoxBonus(id, type) {
  if ($("#promotion_0_" + id + "_" + type).is(":checked")) {
    $(".promotion_0_group_" + id + "_" + type).show();
    $(".promotion_1_group_" + id + "_" + type).hide();
    $(".promotion_2_group_" + id + "_" + type).hide();
  }

  if ($("#promotion_1_" + id + "_" + type).is(":checked")) {
    $(".promotion_0_group_" + id + "_" + type).hide();
    $(".promotion_1_group_" + id + "_" + type).show();
    $(".promotion_2_group_" + id + "_" + type).hide();
    var url = $('.element_data').attr("data-url_promotion_get_setting");
    url = url.replace(':id', id);
    var table_bonus = new LoadBootstrapTable({
      locale: $('.element_data').attr("data-locale"),
      url: url,
      table: '#table_setting_' + id + '_' + type
    });
  }

  if ($("#promotion_2_" + id + "_" + type).is(":checked")) {
    $(".promotion_0_group_" + id + "_" + type).hide();
    $(".promotion_1_group_" + id + "_" + type).hide();
    $(".promotion_2_group_" + id + "_" + type).show();
  }
} // Share khóa học


function shareCourse(id, type) {
  var share_key = Math.random().toString(36).substring(3);

  if (type == 1) {
    var url = $('.element_data').attr("data-url_online_share_course");
  } else {
    var url = $('.element_data').attr("data-url_offline_share_course");
  }

  url = url.replace(':id', id);
  $.ajax({
    type: "POST",
    url: url,
    data: {
      share_key: share_key
    },
    success: function success(data) {
      if (type == 1) {
        var url_link = $('.element_data').attr("data-url_online_detail");
      } else {
        var url_link = $('.element_data').attr("data-url_offline_detail");
      }

      url_link = url_link.replace(':id', id);
      $('#modal-body-share').html('<b>Link share:</b> <span id="link_share' + '">' + url_link + share_key + '</span>');
      $('#btn-copy').html('<button type="button" class="btn" onclick="copyShare(' + id + ',' + type + ')"><i class="fas fa-copy"></i></button>');
      $('#modal-share').modal();
    }
  });
} //COPY LINK


function copyShare(id, type) {
  var copyText = document.getElementById("link_share");

  if (window.getSelection) {
    // other browsers
    var selection = window.getSelection();
    var range = document.createRange();
    range.selectNodeContents(copyText);
    selection.removeAllRanges();
    selection.addRange(range);
    document.execCommand("Copy");
  }
} //MÔ TẢ CHI TIẾT KHÓA HỌC


function openModalDescription(id, type) {
  $.ajax({
    type: "POST",
    url: $('.element_data').attr("data-url_ajax_content_course"),
    data: {
      id: id,
      type: type
    },
    success: function success(data) {
      console.log(data);
      $('#modal-body-description').html(data);
      $('#modal-description').modal();
    }
  });
} //TÓM TẮT KHÓA HỌC


function openModalSummary(id, type) {
  $.ajax({
    type: "POST",
    url: $('.element_data').attr("data-url_ajax_summary_course"),
    data: {
      id: id,
      type: type
    },
    success: function success(data) {
      console.log(data);
      $('#modal-body-summary').html(data.description);
      $('#modal-summary').modal();
    }
  });
} //KHÓA HỌC HẾT HẠN


function endCourse(id, type, status) {
  $('#modal-end-course').modal();

  if (status == '2') {
    $('.modal_body_notification').html("<h3>" + $('.element_data').attr("data-note_course_register_expired") + "</h3>");
    $('.modal_title_notification').html("<span>" + $('.element_data').attr("data-expired_registration") + "</span>");
  } else if (status == '3') {
    $('.modal_body_notification').html("<h3>" + $('.element_data').attr("data-note_course_finished") + "</h3>");
    $('.modal_title_notification').html("<span>" + $('.element_data').attr("data-course_end") + "</span>");
  } else {
    $('.modal_body_notification').html("<h3>" + $('.element_data').attr("data-note_course_pending_approved") + "</h3>");
    $('.modal_title_notification').html("<span>" + $('.element_data').attr("data-course_pending_approved") + "</span>");
  }
} //ĐỐI TƯỢNG


function openModalObject(id, type) {
  $.ajax({
    type: "POST",
    url: $('.element_data').attr("data-url_ajax_object_course"),
    data: {
      id: id,
      type: type
    },
    success: function success(data) {
      var rhtml = '';

      if (data.titles_join) {
        $.each(data.titles_join, function (i, item) {
          rhtml += "<tr>\n                                <td>" + item + "</td>\n                                <td>B\u1EAFt bu\u1ED9c</td>\n                            </tr>";
        });
      }

      if (data.titles_recomment) {
        $.each(data.titles_recomment, function (i, item) {
          rhtml += "<tr>\n                                <td>" + item + "</td>\n                                <td>Khuy\u1EBFn kh\xEDch</td>\n                            </tr>";
        });
      }

      $('#tbody_object').html(rhtml);
      $('#modal_object').modal();
    }
  });
} // ĐĂNG KÝ KHÓA HỌC


function submitRegister(id, type) {
  var answer = window.confirm($('.element_data').attr("data-note_user_want_register"));

  if (answer) {
    if (type == 1) {
      var url_link = $('.element_data').attr("data-url_online_register_course") + "?trainingProgramId=" + trainingProgram;
    } else {
      var url_link = $('.element_data').attr("data-url_offline_register_course");
    }

    url_link = url_link.replace(':id', id);
    $('#frm-course').attr('action', url_link);
    var form = $('#frm-course');
    form.submit();
  }
} // KHẢO SÁT TRƯỚC GHI DANH


function conditionRegister(id, type, survey_register, register_quiz_id, training_program_id) {
  $.ajax({
    type: "POST",
    url: $('.element_data').attr("data-url_condition_register"),
    data: {
      id: id,
      type: type
    },
    success: function success(data) {
      var html = '';

      if (survey_register > 0) {
        var link_survey = '/survey-react/user/' + survey_register + '?courseId=' + id + '&courseType=' + type + '&trainingProgramId=' + training_program_id;

        if (!data.check_survey) {
          var button_survey = '<a href="' + link_survey + '" class="btn">Làm bài</a>';
        } else {
          var button_survey = '<button type="button" class="btn">Đã làm</button>';
        }

        html += "<tr>\n                            <th>Kh\u1EA3o s\xE1t</th>\n                            <th class=\"text-center\">" + (data.check_survey ? '<i class="fas fa-check-circle"></i>' : '<i class="far fa-times-circle"></i>') + "</th>\n                            <th class=\"text-center\">\n                                " + button_survey + "\n                            </th>\n                        </tr>";
      }

      if (register_quiz_id > 0) {
        if (!data.check_quiz) {
          var button_quiz = '<button type="button" onclick="goQuizRegister(' + register_quiz_id + ')" class="btn">Làm bài</button>';
        } else {
          var button_quiz = '<button type="button" class="btn">Đã làm</button>';
        }

        html += "<tr>\n                            <th>K\u1EF3 thi \u0111\u1EA7u v\xE0o</th>\n                            <th class=\"text-center\">" + (data.check_quiz ? '<i class="fas fa-check-circle"></i>' : '<i class="far fa-times-circle"></i>') + "</th>\n                            <th class=\"text-center\">\n                                " + button_quiz + "\n                            </th>\n                        </tr>";
      }

      $('#tbody_condition_register').html(html);
      $('#modal-condition-register').modal();
    }
  });
} // GHI DANH KỲ THI TRƯỚC GHI DANH


function goQuizRegister(register_quiz_id) {
  $.ajax({
    type: "POST",
    url: $('.element_data').attr("data-url_register_quiz"),
    data: {
      id: register_quiz_id
    },
    success: function success(data) {
      window.location.href = data.link_quiz;
    }
  });
} // ĐĂNG KÝ KHÓA HỌC


function notySettingJoin(id, type) {
  var noty = $('.noty_setting_join_' + id + '_' + type).val();
  $('.noty_setting_join').html(noty);
  $('#modal-setting-join').modal();
} // PHẦN TRĂM KHÓA HỌC


var checkNightMode = $('.element_data').attr("data-check_night_mode");
var colorLabel = checkNightMode == 1 ? '#dee2e6' : '#333';

function canvasPercent() {
  $(".canvas_percent").each(function () {
    var value = $(this).val();
    var array_value = value.split(",");
    var percent = array_value[2];
    var status = array_value[3];
    var id = array_value[0];
    var type = array_value[1];

    if (percent >= 0 && status == 4) {
      var myChartCircle = new Chart('chartProgress_' + id + '_' + type, {
        type: 'doughnut',
        data: {
          datasets: [{
            label: $('.element_data').attr("data-completed"),
            percent: percent,
            backgroundColor: ['#5283ff']
          }]
        },
        plugins: [{
          beforeInit: function beforeInit(chart) {
            var dataset = chart.data.datasets[0];
            chart.data.labels = [dataset.label];
            dataset.data = [dataset.percent, 100 - dataset.percent];
          }
        }, {
          beforeDraw: function beforeDraw(chart) {
            var width = chart.chart.width,
                height = chart.chart.height,
                ctx = chart.chart.ctx;
            ctx.restore();
            var fontSize = (height / 100).toFixed(2);
            ctx.font = fontSize + "em sans-serif";
            ctx.fillStyle = colorLabel;
            ctx.textBaseline = "middle";
            var text = parseFloat(chart.data.datasets[0].percent).toFixed(1) + "%",
                textX = Math.round((width - ctx.measureText(text).width) / 2),
                textY = height / 2;
            ctx.fillText(text, textX, textY);
            ctx.save();
          }
        }],
        options: {
          responsive: false,
          legend: {
            labels: {
              fontColor: colorLabel
            },
            display: false
          },
          hover: {
            mode: null
          },
          tooltips: {
            enabled: false
          }
        }
      });
    }
  });
} // MÀU NÚT


function colorButton() {
  var color = $('.element_data').attr("data-color");
  var get_hover_color = $('.element_data').attr("data-get_hover_color");
  $('.btn_register').attr('style', 'background: ' + color + ' !important');
  $('.btn_gocourse').attr('style', 'background: ' + color + ' !important');
  $('.btn_endcourse').attr('style', 'background: #A0A0A0 !important');
  $('.btn_complete').attr('style', 'background: #76C123 !important');
  $(".btn_register").mouseover(function () {
    this.setAttribute('style', 'background: ' + get_hover_color + ' !important');
  });
  $(".btn_register").mouseout(function () {
    this.setAttribute('style', 'background: ' + color + ' !important');
  });
  $(".btn_gocourse").mouseover(function () {
    this.setAttribute('style', 'background: ' + get_hover_color + ' !important');
  });
  $(".btn_gocourse").mouseout(function () {
    this.setAttribute('style', 'background: ' + color + ' !important');
  });
} // HÌNH THỨC HIỂN THỊ


function filterShow() {
  $('#modal-filter-show').modal();
}

function filterDetail() {
  $('.detail_info_course').show();
  $('.chartProgress').hide();
  $('.auth1lnkprce .button_course').css('height', 'unset');
}

function filterBasic(params) {
  $('.detail_info_course').hide();
  $('.chartProgress').hide();
  $('.auth1lnkprce .button_course').css('height', 'unset');
}

function filterProgress() {
  $('.detail_info_course').hide();
  $('.chartProgress').show();
  canvasPercent();
  $('.auth1lnkprce .button_course').css('height', '80px');
} //////////////////
//ẨN HIỆN CÁC LỘ TRÌNH


function showUserTraining() {
  $('.button_show').hide();
  $('.button_hide').show();
  $(".list_user_course").show();
}

function hideUserTraining() {
  $('.button_show').show();
  $('.button_hide').hide();
  $(".list_user_course").hide();
}

$('.datetimepicker').datetimepicker({
  locale: 'vi',
  format: 'DD/MM/YYYY'
});

function bookmarkHandle(id, type) {
  var check = $('.bookmark_' + id + '_' + type).val();

  if (check == 1) {
    var url_bookmark = $('.element_data').attr("data-url_remove_bookmark");
  } else {
    var url_bookmark = $('.element_data').attr("data-url_save_bookmark");
  }

  url_bookmark = url_bookmark.replace(':id', id);
  url_bookmark = url_bookmark.replace(':type', type);
  $.ajax({
    type: "POST",
    url: url_bookmark,
    data: {},
    success: function success(data) {
      $('.bookmark_' + id + '_' + type).val(data.check);

      if (data.check == 1) {
        $('.item_bookmark_' + id + '_' + type).html($('.element_data').attr("data-unbookmark"));
        $('.check_bookmark_' + id + '_' + type).html('<i class="fas fa-heart check-heart" title="Bỏ đánh dấu"></i>');
      } else {
        $('.item_bookmark_' + id + '_' + type).html($('.element_data').attr("data-bookmark"));
        $('.check_bookmark_' + id + '_' + type).html('<i class="far fa-heart" title="Đánh dấu"></i>');
      }
    }
  });
} //////
// DANH SÁCH NẰM NGANG


function horizontalMenu(course_type) {
  var url = $('.element_data').attr("data-url_all_course") + '?list=horizontal';
  window.location.href = url;
} // DANH SÁCH NẰM DỌC


function verticalMenu(course_type) {
  var url = $('.element_data').attr("data-url_all_course") + '?list=vertical';
  window.location.href = url;
}
