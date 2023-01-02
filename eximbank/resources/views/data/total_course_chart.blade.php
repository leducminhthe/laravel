<div class="col-xl-6 col-md-12 pl-0 pr-1">
    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
        <div class="card-header">
            <div class="row">
                <div class="col-10">
                    <span class="bg_title text-white p-2">@lang('ladashboard.online_in_year')</span>
                </div>
                <div class="col-2 p-0">
                    <select name="year" id="year_total_onl" class="form-control select2" data-placeholder="Chọn năm">
                        <option value=""></option>
                        @for ($y = 2021; $y <= date('Y'); $y++)
                            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-5">
            <canvas id="total_onl" class="chartjs"></canvas>
        </div>
    </div>
</div>
<div class="col-xl-6 col-md-12 pl-1 pr-0">
    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
        <div class="card-header">
            <div class="row">
                <div class="col-10">
                    <span class="bg_title text-white p-2">@lang('ladashboard.offline_in_year')</span>
                </div>
                <div class="col-2 p-0">
                    <select name="year" id="year_total_off" class="form-control select2" data-placeholder="Chọn năm">
                        <option value=""></option>
                        @for ($y = 2021; $y <= date('Y'); $y++)
                            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}> {{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-5">
            <canvas id="total_off" class="chartjs"></canvas>
        </div>
    </div>
</div>

<script>
    var checkNightMode = "{{ (session()->exists('nightMode') && session()->get('nightMode') == 1) ? 1 : 0 }}";
    var colorLabel = checkNightMode == 1 ? '#dee2e6' : '#333';

    var onl = document.getElementById("total_onl").getContext('2d');
    var off = document.getElementById("total_off").getContext('2d');
    if (onl !== null) {
        var dataOnl = {
            labels: ["{{ __('ladashboard.uncomplete') }}", "{{ __('ladashboard.completed') }}"],
            datasets: [{
                backgroundColor: [
                    "#FEF200",
                    "#76C123",
                ],
                fill: false,
                data: [{{ implode(',',$chart['onl_year']) }}],
            }]
        };
        var optionsOnl = {
            legend: {
                labels: {
                    fontColor: colorLabel
                },
                display: true,
                position: 'bottom',

            },
            // tooltips: false,
            showTooltips: true,
            elements: {
                arc: {
                    backgroundColor: "#8b1409",
                    hoverBackgroundColor: '#8b1409'
                },
            },
			plugins: {
				labels: {
					fontColor: '#ffffff',
					fontSize: 18,
					render: (args) => {
						return args.value
					}
				},
			}
        };
        var chartOnl = new Chart(onl, {
            type: 'pie',
            data: dataOnl,
            options: optionsOnl
        })
    }
    if (off !== null) {
        var dataOff = {
            labels: ["{{ __('ladashboard.uncomplete') }}", "{{ __('ladashboard.completed') }}"],
            datasets: [{
                backgroundColor: [
                    "#FEF200",
                    "#76C123",
                ],
                fill: false,
                data: [{{ implode(',',$chart['off_year']) }}],
            }]
        };

        var optionsOff = {
            legend: {
                labels: {
                    fontColor: colorLabel
                },
                display: true,
                position: 'bottom',

            },
            // tooltips: true,
            showTooltips: true,
            elements: {
                arc: {
                    backgroundColor: "#8b1409",
                    hoverBackgroundColor: '#8b1409'
                },
            },
			plugins: {
				labels: {
					fontColor: '#ffffff',
					fontSize: 18,
					render: (args) => {
						return args.value
					}
				},
			}
        };
        var chart = new Chart(off, {
            type: 'pie',
            data: dataOff,
            options: optionsOff
        })
    }

    $('#year_total_onl').on('change', function(){
        var year = $('#year_total_onl option:selected').val();

        $.ajax({
            type: "POST",
            url: "{{ route('frontend.home.get_register_online_by_year') }}",
            data:{
                year: year
            },
            success: function (result) {
                if (result) {
                    console.log(result);
                    var dataOnl = {
                        labels: ["{{ __('ladashboard.uncomplete') }}", "{{ __('ladashboard.completed') }}"],
                        datasets: [{
                            backgroundColor: [
                                "#FEF200",
                                "#76C123",
                            ],
                            fill: false,
                            data: result.data,
                        }]
                    };
                    var optionsOnl = {
                        legend: {
                            display: true,
                            position: 'bottom',

                        },
                        // tooltips: false,
                        showTooltips: true,
                        elements: {
                            arc: {
                                backgroundColor: "#8b1409",
                                hoverBackgroundColor: '#8b1409'
                            },
                        },
						plugins: {
							labels: {
								fontColor: '#ffffff',
								fontSize: 18,
								render: (args) => {
									return args.value
								}
							},
						}
                    };
                    var chartOnl = new Chart(onl, {
                        type: 'pie',
                        data: dataOnl,
                        options: optionsOnl
                    })
                    return false;
                } else {
                    console.log('Lỗi hệ thống');

                    return false;
                }
            }
        });
    });

    $('#year_total_off').on('change', function(){
        var year = $('#year_total_off option:selected').val();

        $.ajax({
            type: "POST",
            url: "{{ route('frontend.home.get_register_offline_by_year') }}",
            data:{
                year: year
            },
            success: function (result) {
                if (result) {
                    console.log(result);
                    var dataOff = {
                        labels: ["{{ __('ladashboard.uncomplete') }}", "{{ __('ladashboard.completed') }}"],
                        datasets: [{
                            backgroundColor: [
                                "#FEF200",
                                "#76C123",
                            ],
                            fill: false,
                            data: result.data
                        }]
                    };

                    var optionsOff = {
                        legend: {
                            display: true,
                            position: 'bottom',

                        },
                        // tooltips: true,
                        showTooltips: true,
                        elements: {
                            arc: {
                                backgroundColor: "#8b1409",
                                hoverBackgroundColor: '#8b1409'
                            },
                        },
						plugins: {
							labels: {
								fontColor: '#ffffff',
								fontSize: 18,
								render: (args) => {
									return args.value
								}
							},
						}
                    };
                    var chart = new Chart(off, {
                        type: 'pie',
                        data: dataOff,
                        options: optionsOff
                    })
                    return false;
                } else {
                    console.log('Lỗi hệ thống');

                    return false;
                }
            }
        });
    });
</script>
