@if(count($notify_not_seen) > 0)
    @foreach($notify_not_seen as $notify)
        <div class="row mb-3 border-bottom mx-0 d-flex align-items-center item-notify-old item-notify">
            <div class="col-2">
                <img src="{{ asset('/images/design/moi.png') }}" alt="" class="w-100">
                @if ($notify->viewed == 0)
                    <div class="check_new"></div>
                @endif
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
    @include('themes.mobile.layouts.paginate', ['items' => $notify_not_seen])
@else
    <span class="text-center">@lang('app.no_notice')</span>
@endif
