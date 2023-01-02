@extends('react.layouts.app')
@section('page_title', trans('laforums.forum'))

@section('header')
    <script src="{{ asset('vendor/ckeditor_4.16.2/ckeditor.js') }}" type="text/javascript" charset="utf-8"></script>
@endsection

@section('content')
    <div id="languages"
        data-forum="{{ trans('laforums.forum') }}"
        data-enter_hashtag="{{ trans('laforums.enter_hashtag') }}"
        data-edit="{{ trans('labutton.edit') }}"
        data-delete="{{ trans('labutton.delete') }}"
        data-view="{{ trans('laforums.view') }}"
        data-comment="{{ trans('laforums.comment') }}"
        data-posts="{{ trans('laforums.posts') }}"
        data-want_to_delete="{{ trans('laforums.want_to_delete') }}"
        data-send_new_posts="{{ trans('laforums.send_new_posts') }}"
        data-edit_post="{{ trans('laforums.edit_post') }}"
        data-send_post="{{ trans('laforums.send_post') }}"
        data-add_post="{{ trans('laforums.add_post') }}"
        data-title_post="{{ trans('laforums.title_thread') }}"
        data-home_page="{{ trans('lamenu.home_page') }}"
    >
    </div>
    <div id="react" class="sa4d25 wrapped_forum_react">
                    
    </div>
@endsection
