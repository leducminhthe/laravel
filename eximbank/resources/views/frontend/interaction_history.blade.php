@extends('layouts.app')

@section('page_title', trans('laother.title_project'))

@section('header')

@endsection

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 p-0">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title">
                            <a href="/">
                                <i class="uil uil-apps"></i>
                                <span>{{ trans('lamenu.home_page') }}</span>
                            </a>
                            <i class="uil uil-angle-right"></i>
                            <span class="font-weight-bold">{{ trans('laother.interaction_history') }}</span>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12 text-center">
                    <div id="chartUser" class="h-auto"></div>
                </div>
            </div>
        </div>
    </div>

<script src="{{ asset('js/charts-loader.js') }}" type="text/javascript"></script>
<script type="text/javascript">

    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawBasic);

    function drawBasic() {
        var jsonData = $.ajax({
            type: "POST",
            url: "{{ route('frontend.get_interaction_history') }}",
            dataType: "json",
            async: false,
            data: {}
        }).responseText;

        jsonData = JSON.parse(jsonData);
        var data = google.visualization.arrayToDataTable(jsonData);

        var options = {
            title: '{{ trans("lamenu.summary") }}',
            height: 600,
            bar: {groupWidth: "30%"},
            legend: { position: "none" }
        };

        var chart = new google.visualization.BarChart(document.getElementById('chartUser'));

        chart.draw(data, options);
    }
</script>
@endsection
