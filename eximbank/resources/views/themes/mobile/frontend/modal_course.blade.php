{{-- MODAL CHI TIẾT --}}
<div class="modal fade" id="modal-description">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('labutton.detail') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modal-body-description">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TÓM TẮT --}}
<div class="modal fade" id="modal-summary">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('latraining.brief') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modal-body-summary">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
            </div>
        </div>
    </div>
</div>