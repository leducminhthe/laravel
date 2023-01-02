@if(count($notify_viewed) > 0)
    @foreach($notify_viewed as $notify)
        <div class="row mb-3 border-bottom mx-0 d-flex align-items-center" id="item-notify-viewed">
            <div class="col-2">
                <img src="{{ asset('/images/design/moi.png') }}" alt="" class="w-100">
            </div>
            <div class="col-8 p-0">
                <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.notify.detail', ['id' => $notify->id, 'type' => $notify->type]) }}', 1)">{{ $notify->subject }}</a>
                <p class="mb-0">{{ \Illuminate\Support\Carbon::parse($notify->created_at)->diffForHumans() }}</p>
            </div>
            <div class="col-2 p-0 text-right">
                <span class="btn-delete" data-id="{{ $notify->id .'_'.$notify->type }}">
                    <i class="material-icons text-danger">delete</i>
                </span>
            </div>
        </div>
    @endforeach
    @include('themes.mobile.layouts.paginate', ['items' => $notify_viewed])
@else
    <span class="text-center">@lang('app.no_notice')</span>
@endif


