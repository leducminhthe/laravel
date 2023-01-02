<div id="modalChangeAvatar" class="modal fade " tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('themes.mobile.frontend.profile.change_avatar') }}" method="post" id="form-change-avatar" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">@lang('app.change_avatar')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <input type="file" name="selectavatar" accept="image/*">
                        <br/><em>@lang('app.recommended_size'): 100x100px</em>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn w-100">@lang('app.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
