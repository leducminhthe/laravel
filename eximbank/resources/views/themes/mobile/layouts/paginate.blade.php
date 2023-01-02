<div class="row">
    <div class="col-6">
        @if($items->previousPageUrl())
            <a href="javascript:void(0);" onclick="loadSpinner('{{ $items->previousPageUrl() }}', 0)" class="bp_left">
                <i class="material-icons">navigate_before</i> @lang('app.previous')
            </a>
        @endif
    </div>
    <div class="col-6 text-right">
        @if($items->nextPageUrl())
            <a href="javascript:void(0);" onclick="loadSpinner('{{ $items->nextPageUrl() }}', 0)" class="bp_right">
                @lang('app.next') <i class="material-icons">navigate_next</i>
            </a>
        @endif
    </div>
</div>