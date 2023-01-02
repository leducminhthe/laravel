var checkNightMode = $('.element_data').attr('data-check_night_mode');
var colorLabel = checkNightMode == 1 ? '#dee2e6' : '#333';
var count_complete = $('.element_data').attr('data-check_night_mode');
$('#count-complete').text(count_complete);
var total = $('.element_data').attr('data-training_roadmap_course_count');
var percent = count_complete / total * 100;
var text = (isNaN(percent) ? 0 : percent.toFixed(2)) + '% (' + count_complete + $('.element_data').attr('data-training_roadmap_course_count') + ')';
$('#percent-you').text(text);
$('#percent-you').css('width', (isNaN(percent) ? 0 : percent) + '%');
var chart_course_by_user = document.getElementById("chart_course_by_user").getContext('2d');
var not_learned = $('.element_data').attr('data-not_learned');
var uncomplete = $('.element_data').attr('data-uncomplete');
var completed = $('.element_data').attr('data-completed');
var get_data_chart_course_by_user = $('.element_data').attr('data-chart_course_by_user');
var get_data_chart_course_by_user_array = get_data_chart_course_by_user.split(",");

if (chart_course_by_user !== null) {
  var data_chart_course_by_user = {
    labels: [not_learned, uncomplete, completed],
    datasets: [{
      backgroundColor: ['#F05555', "#f9ee1a", "#76C123"],
      fill: false,
      data: get_data_chart_course_by_user_array
    }]
  };
  var options_chart_course_by_user = {
    legend: {
      labels: {
        fontColor: colorLabel
      },
      display: true,
      position: 'bottom'
    },
    showTooltips: true,
    elements: {
      arc: {
        backgroundColor: "#8b1409",
        hoverBackgroundColor: '#8b1409'
      }
    },
    plugins: {
      labels: {
        fontColor: '#ffffff',
        fontSize: 18,
        render: function render(args) {
          return args.value;
        }
      }
    }
  };
  var chartCourseNewByUser = new Chart(chart_course_by_user, {
    type: 'pie',
    data: data_chart_course_by_user,
    options: options_chart_course_by_user
  });
}

var table_roadmap = new LoadBootstrapTable({
  locale: $('#element_app').attr('data-app_localce'),
  url: $('.element_data').attr('data-url_user_roadmap'),
  table: '#tableroadmap'
});
var chart_subject_by_user = document.getElementById("chart_subject_by_user").getContext('2d');
var get_data_chart_subject_by_user = $('.element_data').attr('data-chart_subject_by_user');
var get_data_chart_subject_by_user_array = get_data_chart_subject_by_user.split(",");

if (chart_subject_by_user !== null) {
  var data_chart_subject_by_user = {
    labels: [uncomplete, completed],
    datasets: [{
      backgroundColor: ["#FEF200", "#76C123"],
      fill: false,
      data: get_data_chart_subject_by_user_array
    }]
  };
  var options_chart_subject_by_user = {
    legend: {
      labels: {
        fontColor: colorLabel
      },
      display: true,
      position: 'bottom'
    },
    showTooltips: true,
    elements: {
      arc: {
        backgroundColor: "#8b1409",
        hoverBackgroundColor: '#8b1409'
      }
    },
    plugins: {
      labels: {
        fontColor: '#ffffff',
        fontSize: 18,
        render: function render(args) {
          return args.value;
        }
      }
    }
  };
  var chartSubjectByUser = new Chart(chart_subject_by_user, {
    type: 'pie',
    data: data_chart_subject_by_user,
    options: options_chart_subject_by_user
  });
} //Khoá học trong ngoài tháp đào tạo


var course_in_out_training_roadmap = document.getElementById("course_in_out_training_roadmap").getContext('2d');
var in_training_roadmap = $('.element_data').attr('data-in_training_roadmap');
var out_training_roadmap = $('.element_data').attr('data-out_training_roadmap');
var get_data_chart_training_roadmap = $('.element_data').attr('data-chart_training_roadmap');
var get_data_chart_training_roadmap_array = get_data_chart_training_roadmap.split(",");

if (course_in_out_training_roadmap !== null) {
  var data_course_in_out_training_roadmap = {
    labels: [in_training_roadmap, out_training_roadmap],
    datasets: [{
      backgroundColor: ['#F05555', "#f9ee1a"],
      fill: false,
      data: get_data_chart_training_roadmap_array
    }]
  };
  var options_course_in_out_training_roadmap = {
    legend: {
      labels: {
        fontColor: colorLabel
      },
      display: true,
      position: 'bottom'
    },
    showTooltips: true,
    elements: {
      arc: {
        backgroundColor: "#8b1409",
        hoverBackgroundColor: '#8b1409'
      }
    },
    plugins: {
      labels: {
        fontColor: '#ffffff',
        fontSize: 18,
        render: function render(args) {
          return args.value;
        }
      }
    }
  };
  var chartCourseInOutTrainingRoadmap = new Chart(course_in_out_training_roadmap, {
    type: 'pie',
    data: data_course_in_out_training_roadmap,
    options: options_course_in_out_training_roadmap
  });
}
