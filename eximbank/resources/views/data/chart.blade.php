<div class="col-md-12 p-0">
    <!-- User activity statistics -->
    <div class="card card-default analysis_card p-0 mt-2" id="user-activity">
        <div class="row no-gutters">
            <div class="col-xl-12">
                <div class="border-right">
                    <div class="card-header justify-content-between">
                        <span class="m-0 bg_title text-white p-2">@lang('ladashboard.analytic')</span>
                        <div class="date-range-report ">
                            <span></span>
                        </div>
                    </div>
                    <ul class="nav nav-tabs justify-content-between justify-content-xl-start nav-fill" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active pb-md-0" data-toggle="tab" href="#user" role="tab" aria-selected="true">
                                <span class="type-name">@lang('ladashboard.onl_course')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pb-md-0" data-toggle="tab" href="#session" role="tab" aria-selected="false">
                                <span class="type-name">@lang('ladashboard.off_course')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pb-md-0" data-toggle="tab" href="#bounce" role="tab" aria-selected="false">
                                <span class="type-name">@lang('ladashboard.quiz')</span>
                            </a>
                        </li>
                    </ul>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="user" role="tabpanel">
                                <canvas id="activity" class="chartjs p-4" style="height: 350px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var checkNightMode = "{{ (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 1 : 0 }}";
    var colorLabel = checkNightMode == 1 ? '#dee2e6' : '#333';

    var activity = document.getElementById("activity");
    if (activity !== null) {
        var activityData = [
            {
                first: [{{ implode(',',$chart['online']) }}],
                second: [{{ implode(',',$chart['onl_complete']) }}]
            },
            {
                first: [{{ implode(',',$chart['offline']) }}],
                second: [{{ implode(',',$chart['off_complete']) }}]
            },
            {
                first: [{{ implode(',',$chart['quiz']) }}],
                second: [{{ implode(',',$chart['quiz_complete']) }}]
            }
        ];

        var config = {
            // The type of chart we want to create
            type: "line",
            // The data for our dataset
            data: {
                labels: [
                    "{{ __('ladashboard.jan') }}",
                    "{{ __('ladashboard.feb') }}",
                    "{{ __('ladashboard.mar') }}",
                    "{{ __('ladashboard.apr') }}",
                    "{{ __('ladashboard.may') }}",
                    "{{ __('ladashboard.jun') }}",
                    "{{ __('ladashboard.jul') }}",
                    "{{ __('ladashboard.aug') }}",
                    "{{ __('ladashboard.sep') }}",
                    "{{ __('ladashboard.oct') }}",
                    "{{ __('ladashboard.nov') }}",
                    "{{ __('ladashboard.dec') }}",
                ],
                datasets: [
                    {
                        label: "{{ trans('ladashboard.register') }}",
                        backgroundColor: "transparent",
                        borderColor: "#d64f43",
                        fill: false,
                        data: activityData[0].first,
                        lineTension: 0.1, 
                        pointRadius: 5,
                        pointBackgroundColor: "rgba(255,255,255,1)",
                        pointHoverBackgroundColor: "rgba(255,255,255,1)",
                        pointBorderWidth: 2,
                        pointHoverRadius: 8,
                        pointHoverBorderWidth: 1
                    },
                    {
                        label: "{{ trans('ladashboard.complete') }}",
                        backgroundColor: "transparent",
                        borderColor: "#e2d927",
                        fill: false,
                        data: activityData[0].second,
                        lineTension: 0.1,
                        borderDash: [10, 5],
                        borderWidth: 1,
                        pointRadius: 5,
                        pointBackgroundColor: "rgba(255,255,255,1)",
                        pointHoverBackgroundColor: "rgba(255,255,255,1)",
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBorderWidth: 1
                    }
                ]
            },
            // Configuration options go here
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    labels: {
                        fontColor: colorLabel
                    },
                    display: false
                },
                scales: {
                    xAxes: [
                        {
                            gridLines: {
                                display: false,
                            },
                            ticks: {
                                fontColor: colorLabel, // this here
                                beginAtZero: true,
                            },
                        }
                    ],
                    yAxes: [
                        {
                            gridLines: {
                                fontColor: "#686f7a",
                                fontFamily: "Roboto, sans-serif",
                                display: true,
                                color: "#efefef",
                                zeroLineColor: "#efefef"
                            },
                            ticks: {
                                // callback: function(tick, index, array) {
                                //   return (index % 2) ? "" : tick;
                                // }
                                // stepSize: 50,
                                fontColor: colorLabel,
                                fontFamily: "Roboto, sans-serif",
                                beginAtZero: true,
                            }
                        }
                    ]
                },
                tooltips: {
                    mode: "index",
                    intersect: false,
                    titleFontColor: "#333",
                    bodyFontColor: "#686f7a",
                    titleFontSize: 12,
                    bodyFontSize: 15,
                    backgroundColor: "rgba(256,256,256,0.95)",
                    displayColors: true,
                    xPadding: 10,
                    yPadding: 7,
                    borderColor: "rgba(220, 220, 220, 0.9)",
                    borderWidth: 2,
                    caretSize: 6,
                    caretPadding: 5
                }
            }
        };

        var ctx = document.getElementById("activity").getContext("2d");
        var myLine = new Chart(ctx, config);

        var items = document.querySelectorAll("#user-activity .nav-tabs .nav-item");
        items.forEach(function(item, index){
            item.addEventListener("click", function() {
                config.data.datasets[0].data = activityData[index].first;
                config.data.datasets[1].data = activityData[index].second;
                myLine.update();
            });
        });
    }
</script>
