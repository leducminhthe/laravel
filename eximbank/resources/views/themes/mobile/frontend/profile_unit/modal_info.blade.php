<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content" id="modalInfoUser">
            <div class="modal-header">
                <h6 class="modal-title">{{ trans('app.info') }}</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="mb-1 text-center">
                    <figure class="avatar avatar-60">
                        <img src="{{ \App\Models\Profile::avatar($profile->user_id) }}" alt="" class="avatar-60">
                    </figure>
                </div>
                <div class="_ttl123_custom mt-0">
                    <b>@lang('laprofile.user_name')</b>
                    <span class="_ttl122_custom">
                        {{ $user->username }}
                    </span>
                </div>
                <div class="_ttl123_custom">
                    <b>@lang('app.employee_code')</b>
                    <span class="_ttl122_custom">
                        {{ $profile->code }}
                    </span>
                </div>
                <div class="_ttl123_custom">
                    <b>@lang('app.full_name')</b>
                    <span class="_ttl122_custom">
                        {{ $profile->full_name }}
                    </span>
                </div>
                <div class="_ttl123_custom">
                    <b>@lang('app.title')</b>
                    <span class="_ttl122_custom">
                        {{ $profile->title_name }}
                    </span>
                </div>
                <div class="_ttl123_custom">
                    <b>@lang('lamenu.unit')</b>
                    <span class="_ttl122_custom">
                        {{ $profile->unit_name }}
                    </span>
                </div>
                <div class="_ttl123_custom">
                    <b>@lang('app.phone')</b>
                    <span class="_ttl122_custom">
                        {{ $profile->phone }}
                    </span>
                </div>
                <div class="_ttl123_custom">
                    <b>Email</b>
                    <span class="_ttl122_custom">
                        {{ $profile->email }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>