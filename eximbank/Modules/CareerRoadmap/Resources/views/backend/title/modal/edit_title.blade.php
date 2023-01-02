<div class="modal fade" id="edit-title-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title2" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <form action="{{ route('module.career_roadmap.title.save_eidt_title', [$title->id]) }}" method="post" class="form-ajax">
        <div class="modal-dialog" role="document">
            <div class="modal-content 1">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title2">@lang('lacareer_path.edit_title')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group" id="roadmap_title">
                        <label>@lang('lacareer_path.title')</label>
                        <select name="title_id" id="roadmap_titles_id" class="form-control load-title" data-placeholder="--- @lang('lacareer_path.title') ---"></select>
                    </div>

                    <div class="form-group" id="set_seniority">
                        <label>{{ trans('lacareer_path.seniority') }}</label>
                        <input type='number' step='0.1' value='' id="seniority_career_title" placeholder='0.0'  name="seniority"  class="form-control"/>
                    </div>

                    <input type="hidden" name="id" value="">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn"><i class="fa fa-save"></i> @lang('labutton.save')</button>
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"></i> @lang('labutton.close')</button>
                </div>
            </div>
        </div>
    </form>
</div>
