<style>
    .card_dash_left{
        width: 67%;
    }
    .card_dash_left h5, 
    .card_dash_left h2{
        text-align: center;
    }
    .card_dash_right{
        width: 33%;
    }
    .card_dash {
        border-radius: 10px;
        box-shadow: rgb(0 0 0 / 16%) 0px 1px 4px;
    }
</style>
<div class="row my-3">
    <div class="col-xl-3 col-lg-6 col-md-6 p-1">
        <div class="card_dash p-2 mt-0 d_flex_align">
            <div class="card_dash_left">
                <h5><a href="{{ route('module.dashboard_teacher_detail') }}">@lang('ladashboard.total_lecture_hours')</a></h5>
                <h4 class="text-center">{{ $totalTimeTaught }} giờ</h4>
            </div>
            <div class="card_dash_right">
                <img src="{{ asset('images/dashboard/graduation-cap.svg') }}" alt="" width="65px" height="65px">
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 p-1">
        <div class="card_dash p-2 mt-0 d_flex_align">
            <div class="card_dash_left">
                <h5><a href="{{ route('module.dashboard_teacher_detail') }}">@lang('ladashboard.number_classes_taught')</a></h5>
                <h4 class="text-center">{{ $totalClassTaught }}</h4>
            </div>
            <div class="card_dash_right">
                <img src="{{ asset('images/dashboard/training.svg') }}" alt="" width="65px" height="65px">
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 p-1">
        <div class="card_dash p-2 mt-0 d_flex_align">
            <div class="card_dash_left">
                <h5><a href="{{ route('module.dashboard_teacher_detail') }}">@lang('ladashboard.number_classes_not_taught')</a></h5>
                <h4 class="text-center">{{ $totalClassNotTaught }}</h4>
            </div>
            <div class="card_dash_right">
                <img src="{{ asset('images/dashboard/online-course.svg') }}" alt="" width="65px" height="65px">
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 p-1">
        <div class="card_dash p-2 mt-0 d_flex_align">
            <div class="card_dash_left">
                <h5><a href="{{ route('module.dashboard_teacher_detail') }}">@lang('lareport.total_cost')</a></h5>
                <h5 class="text-center">{{ $totalCostTaught }} vnđ</h5>
            </div>
            <div class="card_dash_right">
                <img src="{{ asset('images/dashboard/knowledge.svg') }}" alt="" width="65px" height="65px">
            </div>
        </div>
    </div>
</div>
