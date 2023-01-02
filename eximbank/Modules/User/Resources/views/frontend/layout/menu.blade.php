<div class="row pb-2 user-info">
    <div class="col-md-12 text-center">
        <a href="{{route('module.frontend.user.info')}}" class="btn">
            <div><i class="fa fa-user"></i></div>
            <div>{{ trans('lamenu.user_info') }}</div>
        </a>
        <a href="{{route('module.frontend.user.trainingprocess')}}" class="btn">
            <div><i class="fa fa-hashtag" aria-hidden="true"></i></div>
            <div>{{ trans('laprofile.training_process') }}</div>
        </a>
        <a href="{{route('module.frontend.user.quizresult')}}" class="btn">
            <div><i class="fa fa-graduation-cap" aria-hidden="true"></i></div>
            <div>{{ trans('laprofile.quiz_result') }}</div>
        </a>
        <a href="{{route('module.frontend.user.roadmap')}}" class="btn">
            <div><i class="fa fa-sun-o" aria-hidden="true"></i></div>
            <div>{{trans('laprofile.trainingroadmap')}}</div>
        </a>
    </div>
</div>
