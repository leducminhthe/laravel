@extends('react.layouts.app')
@section('page_title', trans('lavideo_training_materials.training_video'))

@section('header')
    <script src="{{ asset('js/dropzone.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <div id="languages"
        data-training_video="{{ trans('lavideo_training_materials.training_video') }}"
        data-enter_name_video="{{ trans('lavideo_training_materials.enter_name_video') }}"
        data-category="{{ trans('lavideo_training_materials.category') }}"
        data-add_video="{{ trans('lavideo_training_materials.add_video') }}"
        data-saved_video="{{ trans('lavideo_training_materials.saved_video') }}"
        data-my_video="{{ trans('lavideo_training_materials.my_video') }}"
        data-all_video="{{ trans('lavideo_training_materials.all_video') }}"
        data-delete="{{ trans('labutton.delete') }}"
        data-enter_name="{{ trans('lavideo_training_materials.enter_name') }}"
        data-save="{{ trans('lavideo_training_materials.save') }}"
        data-search_video="{{ trans('lavideo_training_materials.search_video') }}"
        data-view="{{ trans('lavideo_training_materials.view') }}"
        data-dislike="{{ trans('lavideo_training_materials.dislike') }}"
        data-saved="{{ trans('lavideo_training_materials.saved') }}"
        data-video_same_category="{{ trans('lavideo_training_materials.video_same_category') }}"
        data-comment="{{ trans('lavideo_training_materials.comment') }}"
        data-write_comment="{{ trans('lavideo_training_materials.write_comment') }}"
        data-home_page="{{ trans('lamenu.home_page') }}"
        >
    </div>
    <div id="react" class="sa4d25">
                    
    </div>
@endsection
