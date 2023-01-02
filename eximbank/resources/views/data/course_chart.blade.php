<div class="col-xl-12 col-md-12 p-0">
    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
        <div class="card-header">
            <span class="bg_title text-white p-2">@lang('ladashboard.course_in_year')</span>
        </div>
        <div class="card-body p-5" style="height: 450px;">
            <canvas id="barChart" class="chartjs"></canvas>
        </div>
    </div>
</div>
<script>
    var checkNightMode = "{{ (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 1 : 0 }}";
    var colorLabel = checkNightMode == 1 ? '#dee2e6' : '#333';
    
    var cUser = document.getElementById("barChart");
    if (cUser !== null) {
        var myUChart = new Chart(cUser, {
            type: "bar",
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
                        label: "{{ __('ladashboard.onl_course') }}",
                        data: [{{ implode(',',$chart['online']) }}],
                        backgroundColor: "#d64f43",
                    },
                    {
                        label: "{{ __('ladashboard.off_course') }}",
                        data: [{{ implode(',',$chart['offline']) }}],
                        backgroundColor: "#e2d927"
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    labels: {
                        fontColor: colorLabel
                    },
                    display: true
                },
                plugins: {
					labels: false,
				}
            }
        });
    }
</script>
