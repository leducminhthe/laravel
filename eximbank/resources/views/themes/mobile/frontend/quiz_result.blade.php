@extends('themes.mobile.layouts.app')

@section('page_title', data_locale('Kết quả thi', 'Quiz Result'))

@section('content')
    <div class="container wrraped_training_process mt-2">
        <div class="row">
            <div class="col-12">
                <div class="list-group list-group-flush" id="training-process">
                    @if(count($rows) > 0)
                        @foreach($rows as $item)
                            <div class="row mb-2 bg-white shadow border">
                                <div class="col-12 info my-1">
                                    <p class="mb-0">
                                        {{ $item->name }} ({{ $item->code }})
                                    </p>
                                    <p class="text-mute mt-1 mb-0">
                                        {{ get_date($item->start_date) }} @if($item->end_date) {{ ' - '. get_date($item->end_date) }} @endif
                                    </p>
                                    <div class="row">
                                        <div class="col-6">
                                            {{ data_locale('Thời gian thi', 'Limit time') }} (Phút): {{ $item->limit_time }} 
                                        </div>
                                        <div class="col-6 text-right">
                                            {{ data_locale('Điểm', 'Score') }}: {{ $item->grade }}
                                        </div>
                                    </div>
                                    <p class="text-mute">
                                        <span class="{{ $item->result ? 'complete_process' : 'uncomplete_process' }}">
                                            {{ $item->result ? trans("backend.finish") : trans("backend.incomplete") }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                        <div class="row mt-2">
                            <div class="col-12 px-0 text-right">
                                {{ $rows->links('themes/mobile/layouts/pagination') }}
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-12 text-center">
                                <span>@lang('app.not_found')</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

