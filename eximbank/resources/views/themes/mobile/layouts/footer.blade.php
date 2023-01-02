@php
    $arr = ['themes.mobile.frontend.home', 'frontend.home'];

    $routeName = Route::currentRouteName();
    $tabs_course = Request::segment(2);
@endphp
@php
    $get_notes = \App\Models\Note::where('type',0)->where('user_id',profile()->user_id)->get();
    $date = date('Y-m-d H:i:s');
@endphp
@if (!empty($get_notes))
    @foreach ($get_notes as $key => $get_note)
        @if (!empty($get_note->date_time) && $date > $get_note->date_time)
            <div class="show_note_mobile" id="note_id_{{$get_note->id}}">
                <div class="close_show_note_mobile">
                    <button class="btn text-white" type="button" onclick="closeShowNote({{$get_note->id}})">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <div class="note_mobile">
                    <h5>{{ trans('latraining.note') }}</h5>
                </div>
                <div class="content_note_mobile">
                    <span>{{$get_note->content}}</span>
                </div>
            </div>
        @endif
    @endforeach
@endif

<div class="footer">
    <div class="no-gutters">
        <div class="col-auto mx-auto">
            <div class="row no-gutters justify-content-center text-center">
                @if (in_array($routeName, ['themes.mobile.frontend.offline.detail', 'themes.mobile.frontend.online.detail']))
                    <div class="col-12" id="content_footer">
                        @yield('content_footer')
                    </div>
                @else
                    <div class="col-3">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.home') }}', 1, 2)" class="btn btn-link-default bg-white {{ (isset($lay) && $lay == 'home') ? 'active' : '' }}">
                            <img src="{{ asset('themes/mobile/img/home.png') }}" alt="">
                            <div class="small color_text_footer">Home</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.note_mobile.index') }}', 1, 2)" class="btn btn-link-default bg-white {{ (isset($lay) && $lay == 'note-mobile') ? 'active' : '' }}">
                            <img src="{{ asset('themes/mobile/img/notepad.png') }}" alt="">
                            <div class="small color_text_footer">{{ trans('latraining.note') }}</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.suggest.index') }}', 1, 2)" class="btn btn-link-default bg-white {{ (isset($lay) && $lay == 'suggest') ? 'active' : '' }}">
                            <img src="{{ asset('themes/mobile/img/feedback_footer.png') }}" alt="">
                            <div class="small color_text_footer">@lang('app.suggest')</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.frontend.profile') }}', 1, 3)" class="btn btn-link-default bg-white {{ (isset($lay) && $lay == 'profile') ? 'active' : '' }}">
                            <img src="{{ asset('themes/mobile/img/user_first.png') }}" alt="">
                            <div class="small color_text_footer">@lang('app.account')</div>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
