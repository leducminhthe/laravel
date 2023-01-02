@extends('react.layouts.app')
@section('page_title', trans('lanote.note'))

@section('content')
    <div id="languages"
        data-date_created="{{ trans('lanote.created_at') }}"
        data-note="{{ trans('lanote.note') }}"
        data-delete="{{ trans('labutton.delete') }}"
        data-year="{{ trans('lanote.year') }}"
        data-home_page="{{ trans('lamenu.home_page') }}"
    >
    </div>
    <div id="react" class="sa4d25">
                    
    </div>
@endsection
