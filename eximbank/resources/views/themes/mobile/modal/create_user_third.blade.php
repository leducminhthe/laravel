<div id="userThird" class="modal fade " tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('auth.save_user_third') }}" method="post" id="form-create-user" enctype="multipart/form-data" class="form-ajax form-validate">
                @csrf
                <div class="modal-header d-block text-center">
                    <h4 class="modal-title">@lang('app.register')</h4>
                    {{--  <button type="button" class="close" data-dismiss="modal">&times;</button>  --}}
                </div>
                <div class="modal-body text-center">
                    <div id="notify_register" class="pb-2 text-danger h6"></div>

                    <div class="text-center">
                        <input type="text" name="username" class="form-control" placeholder="{{ trans('backend.user_name') }} (*)" required>
                    </div>
                    <div class="text-center">
                        <input type="password" name="password" class="form-control" placeholder="{{ trans('backend.pass') }} (*)" required>
                    </div>
                    <div class="text-center">
                        <input type="text" name="lastname" class="form-control" placeholder="{{ trans('backend.they_staff') }} (*)" required>
                    </div>
                    <div class="text-center">
                        <input type="text" name="firstname" class="form-control" placeholder="Tên người dùng (*)" required>
                    </div>
                    <div class="text-center">
                        <input type="text" name="email" class="form-control" placeholder="Email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn_register" class="btn btn-default w-100">@lang('app.register')</button>
                </div>
            </form>
        </div>
    </div>
</div>
