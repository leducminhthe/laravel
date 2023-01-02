@php
    $date = date('Y-m-d H:i:s');
    $background_button = $get_color_button->background;
    $hover_background_button = $get_color_button->hover_background;
@endphp
<style>
    .all_menu_bottom .btn {
        background: {{ $background_button . ' !important' }};
    }
    .all_menu_bottom .btn:hover {
        background: {{ $hover_background_button . ' !important' }};
    }
    .btn_to_top {
        background: {{ $background_button . ' !important' }};
    }
</style>
@if (!empty($menuBottom))
    @foreach ($menuBottom as $key => $get_note)
        @if ($date > $get_note->date_time && $get_note->date_time !== '1970-01-01 08:00:00')
            <input type="hidden" id="test" value="1">
            <div class="show_note" id="note_id_{{$get_note->id}}" onload="note()">
                <div class="close_show_note">
                    <button class="btn" type="button" onclick="closeShowNote({{$get_note->id}})">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <div class="note">
                    <h3><img src="{{ asset('images/note.png') }}" alt=""></h3>
                </div>
                <div class="content_note">
                    <span>{{$get_note->content}}</span>
                </div>
                <div class="song">
                    <div class="player">
                        <audio class="audio" id="my_audio" autoplay controls>
                            <source src="{{ url('images/got-it-done.mp3') }}" type="audio/mpeg">
                            <source src="{{ url('images/got-it-done.mp3') }}" type="audio/ogg">
                        </audio>
                    </div>
                </div>
                <div class="pause noti_note" onclick="togglePlay()">
                    <i class="uil uil-bell"></i>
                </div>
            </div>
        @endif
    @endforeach
@endif
<button class="btn btn_to_top" type="button" onclick="topFunction()">
    <img class="icon_menu_bottom" src="{{ asset('images/up-arrow.png') }}" alt="" width="25px">
</button>
<div class="all_menu_bottom button_menu_bottom m-0">
    <div class="pull-right">
        <div class="btn-group">
            <button class="btn" id="show_menu" type="button">
                <img class="icon_menu_bottom" src="{{ asset('images/menu_bottom.png') }}" alt="" width="25px">
            </button>
            <div class="all_item_menu">
                <div class="item_menu item_suggest" onmouseover="change('item_suggest')" onmouseout="back('item_suggest')">
                    <button id="create_suggest" class="btn" type="button">
                        <i class='uil uil-comment-alt-exclamation' aria-hidden="true"></i>
                        <div class="title_item">
                            <span>{{ trans('lamenu.suggestion') }}</span>
                        </div>
                    </button>
                </div>
                <div class="item_menu item_contact" onmouseover="change('item_contact')" onmouseout="back('item_contact')">
                    <a href="{{ route('frontend.contact') }}" class="btn">
                        <i class='fas fa-comments' aria-hidden="true"></i>
                        <div class="title_item">
                            <span>{{ trans('lasetting.contact') }}</span>
                        </div>
                    </a>
                </div>
                <div class="item_menu item_location" onmouseover="change('item_location')" onmouseout="back('item_location')">
                    <a href="{{ route('frontend.google.map') }}" class="btn">
                        <i class='fas fa-map-marker-alt' aria-hidden="true"></i>
                        <div class="title_item">
                            <span>{{ trans('lasetting.training_position') }}</span>
                        </div>
                    </a>
                </div>
                <div class="item_menu item_note" onmouseover="change('item_note')" onmouseout="back('item_note')">
                    <button id="note_menu" class="btn" type="button">
                        <i class="far fa-sticky-note"></i>
                        <div class="title_item">
                            <span>{{ trans('latraining.note') }}</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL GÓP Ý --}}
<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form action="" method="post" class="form-ajax" id="form_suggest" onsubmit="return false;">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class='uil uil-comment-alt-exclamation' aria-hidden="true"></i> {{ trans('lasuggest.add_suggest') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-3 label">
                            <label> {{ trans('lasuggest.name_suggest') }}</label>
                        </div>
                        <div class="col-md-9">
                            <input class="form-control" name="name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 label">
                            <label> {{ trans('latraining.content') }}</label>
                        </div>
                        <div class="col-md-9">
                            <textarea class="form-control content_suggest" name="content" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="saveSuggest(event)" class="save_suggest btn">{{ trans('labutton.save') }}</button>
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL GHI CHÚ --}}
<div class="modal fade" id="modal-create-note" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form action="{{ route('frontend.save.note') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <img src="{{ asset('images/note.png') }}" alt="">
                        {{ trans('laother.add_new_note') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body-note">
                    <input type="hidden" name="type" value="0">
                    <div class="row">
                        <div class="col-md-8">
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="btn-group act-btns">
                                <button type="button" onclick="addNewNote()" class="btn"><i class="fa fa-plus-circle"></i> &nbsp;{{ trans('laother.add_new_note') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 label">
                            <label>{{ trans('laother.notice_time') }}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="datetime-local" id="date_time" class="w-100" name="date_times[]" onkeydown="dateTime(e)">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 label">
                            <label>{{ trans('laother.content_note') }}</label>
                        </div>
                        <div class="col-md-7">
                            <textarea class="form-control" name="contents[]" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn">{{ trans('labutton.save') }}</button>
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<input type="hidden" id="close_note" value="{{ route('frontend.close.note') }}">
<input type="hidden" id="save_suggest" value="{{ route('module.suggest.save') }}">
<input type="hidden" id="laother_processing" value="{{ route('module.suggest.save') }}">
<input type="hidden" id="save_suggest" value="{{ route('module.suggest.save') }}">
<div id="languages_menu_bottom" 
    data-processing="{{ trans("laother.processing") }}"
    data-notice_time="{{ trans("laother.notice_time") }}"
    data-content_note="{{ trans("laother.content_note") }}"
    data-delete="{{ trans("labutton.delete") }}"
>
</div>
