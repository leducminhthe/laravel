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
</style>
<div class="row mt-3">
    <div class="col-xl-3 col-lg-6 col-md-6 p-1">
        <div class="card_dash p-2 mt-0">
            <div class="card_dash_left">
                <h5><a href="{{ route('frontend.all_course',['type' => 3]) }}">@lang('ladashboard.onl_course')</a></h5>
                <h2>{{ $countMyOnlineCourse }}</h2>
            </div>
            <div class="card_dash_right">
                <img src="{{ asset('images/dashboard/graduation-cap.svg') }}" alt="">
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 p-1">
        <div class="card_dash p-2 mt-0">
            <div class="card_dash_left">
                <h5><a href="{{ route('frontend.all_course',['type' => 3]) }}">@lang('ladashboard.off_course')</a></h5>
                <h2>{{ $countMyOfflineCourse }}</h2>
            </div>
            <div class="card_dash_right">
                <img src="{{ asset('images/dashboard/training.svg') }}" alt="">
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 p-1">
        <div class="card_dash p-2 mt-0">
            <div class="card_dash_left">
                <h5><a href="{{ route('quiz_react') }}">@lang('ladashboard.quiz')</a></h5>
                <h2>{{ $count_quiz }}</h2>
            </div>
            <div class="card_dash_right">
                <img src="{{ asset('images/dashboard/online-course.svg') }}" alt="">
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 p-1">
        <div class="card_dash p-2 mt-0">
            <div class="card_dash_left">
                <h5><a href="{{ route('module.frontend.userpoint.history') }}">@lang('ladashboard.your_accumulated_points')</a></h5>
                <h2>{{ $point }}</h2>
            </div>
            <div class="card_dash_right">
                <img src="{{ asset('images/dashboard/knowledge.svg') }}" alt="">
            </div>
        </div>
    </div>
</div>
