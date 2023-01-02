function rating_url_formatter(value, row, index) {
  if (row.rating_level_url) {
    return '<a href="' + row.rating_level_url + '" class="btn">Đánh giá</a>';
  }

  return '<span>Đánh giá <i class="fa fa-info-circle view-notify-rating" data-notify_rating="' + row.notify_rating + '" ></i></span>';
}

function course_name_formatter(value, row, index) {
  if (row.course_name) {
    return '<a href="javascript:void(0)" title="' + row.course_info + '">' + row.course_name + '</a>';
  }

  return '-';
}

function add_colleague_formatter(value, row, index) {
  if (row.colleague) {
    return '<a href="javascript:void(0)" class="btn load-modal" data-url="' + row.modal_object_colleague_url + '"> <i class="fa fa-user"></i> </a>';
  }

  return '';
}

var table = new LoadBootstrapTable({
  locale: $('#element_app').attr("data-app_localce"),
  url: $('.element_data').attr("data-url_rating_level"),
  table: '#table-rating-level'
});
$('#table-rating-level').on('click', '.view-notify-rating', function () {
  var text = '';
  var notify_rating = $(this).data('notify_rating');

  if (notify_rating) {
    notify_rating = notify_rating.split(',');

    for (var i = 0; i < notify_rating.length; i++) {
      text += '<p>' + (i + 1) + '. ' + notify_rating[i] + '</p>';
    }

    $('#modal-notify-rating .modal-body').html(text);
    $('#modal-notify-rating').modal();
  }
});
