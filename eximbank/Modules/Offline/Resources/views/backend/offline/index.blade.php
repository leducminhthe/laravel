{{-- @extends('layouts.backend')
{{-- @extends('layouts.backend')

@section('page_title', trans('backend.offline_course'))

@section('breadcrumb')
<div class="ibox-content forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('lamenu.training') }} <i class="uil uil-angle-right"></i>
        <span class="font-weight-bold">{{ trans('backend.offline_course') }}</span>
    </h2>
</div>
@endsection

@section('content') --}}
<div role="main">
    @if(isset($errors))
        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach
    @endif
    <div class="row">
        <div class="col-md-12  act-btns">
            <div class="pull-right">
                @include('offline::backend.offline.filter')
                <div class="wrraped_offline text-right">
                    <div class="btn-group">
                        @canany(['offline-course-create', 'offline-course-edit'])
                            <button class="btn" id="model-list-template-import">
                                <i class="fa fa-download"></i> {{ trans('labutton.import_template') }}
                            </button>
                            <button class="btn" id="model-list-import">
                                <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                            </button>
                        @endcan
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                                {{ trans('labutton.task') }}
                            </button>
                            <div class="dropdown-menu"  role="menu" aria-labelledby="dropdownMenuButton">
                                @can('offline-course-duplicate')
                                    <a class="dropdown-item p-1 copy" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 60 60" width="512">
                                            <g id="Page-1" fill-rule="evenodd"><g id="134---Copy-Files" fill-rule="nonzero"><path id="Shape" d="m29 6h6c.5522847 0 1-.44771525 1-1s-.4477153-1-1-1h-6c-.5522847 0-1 .44771525-1 1s.4477153 1 1 1z"/><path id="Shape" d="m39 6h2c.5522847 0 1-.44771525 1-1s-.4477153-1-1-1h-2c-.5522847 0-1 .44771525-1 1s.4477153 1 1 1z"/><path id="Shape" d="m9 20h9c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-9c-.55228475 0-1 .4477153-1 1s.44771525 1 1 1z"/><path id="Shape" d="m23 20h15c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-15c-.5522847 0-1 .4477153-1 1s.4477153 1 1 1z"/><path id="Shape" d="m38 23h-9c-.5522847 0-1 .4477153-1 1s.4477153 1 1 1h9c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1z"/><path id="Shape" d="m9 25h15c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-15c-.55228475 0-1 .4477153-1 1s.44771525 1 1 1z"/><path id="Shape" d="m19 29c0-.5522847-.4477153-1-1-1h-9c-.55228475 0-1 .4477153-1 1s.44771525 1 1 1h9c.5522847 0 1-.4477153 1-1z"/><path id="Shape" d="m38 28h-15c-.5522847 0-1 .4477153-1 1s.4477153 1 1 1h15c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1z"/><path id="Shape" d="m55 4h-9.1c-.4784327-2.3264168-2.5248992-3.99700171-4.9-4h-27c-.2651948.00005664-.5195073.10545063-.707.293l-13 13c-.18754937.1874927-.29294336.4418052-.293.707v37c.00330612 2.7600532 2.23994685 4.9966939 5 5h9.1c.4784327 2.3264168 2.5248992 3.9970017 4.9 4h36c2.7600532-.0033061 4.9966939-2.2399468 5-5v-46c-.0033061-2.76005315-2.2399468-4.99669388-5-5zm-13 44v-.5c0-.8284271-.6715729-1.5-1.5-1.5h-10.5v-4h10.5c.8284271 0 1.5-.6715729 1.5-1.5v-.5l5.334 4zm-29-44.586v6.586c0 1.6568542-1.3431458 3-3 3h-6.586zm-11 47.586v-36h8c2.7600532-.0033061 4.9966939-2.2399468 5-5v-8h26c1.6568542 0 3 1.34314575 3 3v34l-1.6-1.2c-.4545265-.3408949-1.0626444-.3957288-1.5708204-.1416408s-.8291796.7734827-.8291796 1.3416408v1h-10.5c-.8284271 0-1.5.6715729-1.5 1.5v5c0 .8284271.6715729 1.5 1.5 1.5h10.5v1c0 .5681581.3210036 1.0875528.8291796 1.3416408s1.1162939.1992541 1.5708204-.1416408l1.6-1.2v2c0 1.6568542-1.3431458 3-3 3h-36c-1.65685425 0-3-1.3431458-3-3zm56 4c0 1.6568542-1.3431458 3-3 3h-36c-1.2667854-.0052514-2.3937454-.8056491-2.816-2h24.816c2.7600532-.0033061 4.9966939-2.2399468 5-5v-3.5l3.067-2.3c.3777088-.2832816.6-.727864.6-1.2s-.2222912-.9167184-.6-1.2l-3.067-2.3v-6.5h6c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-6v-3h6c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-6v-3h6c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-6v-12h3c.5522847 0 1-.44771525 1-1s-.4477153-1-1-1h-3v-2h9c1.6568542 0 3 1.34314575 3 3z"/><path id="Shape" d="m55 8h-2c-.5522847 0-1 .44771525-1 1s.4477153 1 1 1h2c.5522847 0 1-.44771525 1-1s-.4477153-1-1-1z"/></g></g>
                                        </svg> {{ trans('labutton.copy') }}
                                    </a>
                                @endcan
                                @can('offline-course-status')
                                    <a class="dropdown-item p-1" onclick="changeStatus(0,1)" data-status="1" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g><path d="M373.333,160c-52.928,0-96,43.072-96,96s43.072,96,96,96c52.928,0,96-43.072,96-96S426.261,160,373.333,160z     M373.333,330.667c-41.173,0-74.667-33.493-74.667-74.667s33.493-74.667,74.667-74.667C414.507,181.333,448,214.827,448,256    S414.507,330.667,373.333,330.667z"/></g></g><g><g><path d="M373.333,117.333H138.667C62.208,117.333,0,179.541,0,256s62.208,138.667,138.667,138.667h234.667    C449.792,394.667,512,332.459,512,256S449.792,117.333,373.333,117.333z M373.333,373.333H138.667    c-64.683,0-117.333-52.629-117.333-117.333s52.651-117.333,117.333-117.333h234.667c64.683,0,117.333,52.629,117.333,117.333    S438.016,373.333,373.333,373.333z"/></g></g><g><g><path d="M117.333,202.667c-17.643,0-32,14.357-32,32v42.667c0,17.643,14.357,32,32,32c17.643,0,32-14.357,32-32v-42.667    C149.333,217.024,134.976,202.667,117.333,202.667z M128,277.333c0,5.888-4.8,10.667-10.667,10.667    c-5.867,0-10.667-4.779-10.667-10.667v-42.667c0-5.888,4.8-10.667,10.667-10.667C123.2,224,128,228.779,128,234.667V277.333z"/></g></g><g><g><path d="M224,202.667c-5.888,0-10.667,4.779-10.667,10.667v40.149l-22.443-44.928c-2.219-4.416-7.104-6.763-12.011-5.611    c-4.821,1.131-8.213,5.44-8.213,10.389v85.333c0,5.888,4.779,10.667,10.667,10.667S192,304.555,192,298.667v-40.149l22.464,44.928    c1.835,3.669,5.547,5.888,9.536,5.888c0.811,0,1.621-0.085,2.453-0.277c4.821-1.131,8.213-5.44,8.213-10.389v-85.333    C234.667,207.445,229.888,202.667,224,202.667z"/></g></g></svg>
                                        {{ trans('labutton.enable') }}
                                    </a>
                                    <a class="dropdown-item p-1" onclick="changeStatus(0,0)" data-status="0" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g><path d="M138.667,160c-52.928,0-96,43.072-96,96s43.072,96,96,96c52.928,0,96-43.072,96-96S191.595,160,138.667,160z     M138.667,330.667C97.493,330.667,64,297.173,64,256s33.493-74.667,74.667-74.667s74.667,33.493,74.667,74.667    S179.84,330.667,138.667,330.667z"/></g></g><g><g><path d="M373.333,117.333H138.667C62.208,117.333,0,179.541,0,256s62.208,138.667,138.667,138.667h234.667    C449.792,394.667,512,332.459,512,256S449.792,117.333,373.333,117.333z M373.333,373.333H138.667    c-64.683,0-117.333-52.629-117.333-117.333s52.651-117.333,117.333-117.333h234.667c64.683,0,117.333,52.629,117.333,117.333    S438.016,373.333,373.333,373.333z"/></g></g><g><g><path d="M288,202.667c-17.643,0-32,14.357-32,32v42.667c0,17.643,14.357,32,32,32s32-14.357,32-32v-42.667    C320,217.024,305.643,202.667,288,202.667z M298.667,277.333c0,5.888-4.8,10.667-10.667,10.667s-10.667-4.779-10.667-10.667    v-42.667c0-5.888,4.8-10.667,10.667-10.667s10.667,4.779,10.667,10.667V277.333z"/></g></g><g><g><path d="M384,202.667h-32c-5.888,0-10.667,4.779-10.667,10.667v85.333c0,5.888,4.779,10.667,10.667,10.667    c5.888,0,10.667-4.779,10.667-10.667V224H384c5.888,0,10.667-4.779,10.667-10.667S389.888,202.667,384,202.667z"/></g></g><g><g><path d="M373.333,245.333H352c-5.888,0-10.667,4.779-10.667,10.667s4.779,10.667,10.667,10.667h21.333    c5.888,0,10.667-4.779,10.667-10.667S379.221,245.333,373.333,245.333z"/></g></g><g><g><path d="M448,202.667h-32c-5.888,0-10.667,4.779-10.667,10.667v85.333c0,5.888,4.779,10.667,10.667,10.667    c5.888,0,10.667-4.779,10.667-10.667V224H448c5.888,0,10.667-4.779,10.667-10.667S453.888,202.667,448,202.667z"/></g></g><g><g><path d="M437.333,245.333H416c-5.888,0-10.667,4.779-10.667,10.667s4.779,10.667,10.667,10.667h21.333    c5.888,0,10.667-4.779,10.667-10.667S443.221,245.333,437.333,245.333z"/></g></g></svg>
                                        {{ trans('labutton.disable') }}
                                    </a>
                                @endcan
                                @can('offline-course-approve')
                                    <a class="dropdown-item p-1 approved" data-model="el_offline_course" data-status="1" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Icons" enable-background="new 0 0 128 128" height="512" viewBox="0 0 128 128" width="512"><path id="Check_Mark" d="m64 128c-35.289 0-64-28.711-64-64s28.711-64 64-64 64 28.711 64 64-28.711 64-64 64zm0-120c-30.879 0-56 25.121-56 56s25.121 56 56 56 56-25.121 56-56-25.121-56-56-56zm-9.172 78.828 40-40c1.563-1.563 1.563-4.094 0-5.656s-4.094-1.563-5.656 0l-37.172 37.172-13.172-13.172c-1.563-1.563-4.094-1.563-5.656 0s-1.563 4.094 0 5.656l16 16c.781.781 1.805 1.172 2.828 1.172s2.047-.391 2.828-1.172z"/></svg> {{ trans('labutton.approve') }}
                                    </a>
                                    <a class="dropdown-item p-1 approved" data-model="el_offline_course" data-status="0" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 16 16" width="512"><g id="_19" data-name="19"><path d="m8 16a8 8 0 1 1 8-8 8 8 0 0 1 -8 8zm0-15a7 7 0 1 0 7 7 7 7 0 0 0 -7-7z"/><path d="m8.71 8 3.14-3.15a.49.49 0 0 0 -.7-.7l-3.15 3.14-3.15-3.14a.49.49 0 0 0 -.7.7l3.14 3.15-3.14 3.15a.48.48 0 0 0 0 .7.48.48 0 0 0 .7 0l3.15-3.14 3.15 3.14a.48.48 0 0 0 .7 0 .48.48 0 0 0 0-.7z"/></g></svg> {{ trans('labutton.deny') }}
                                    </a>
                                @endcan
                                @canany(['offline-course-create', 'offline-course-edit'])
                                    <a class="dropdown-item p-1" onclick="lockCourse(0,1)" data-status="1" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g><g><path d="M401.067,238.933v-93.867C401.067,65.075,335.992,0,256,0S110.933,65.075,110.933,145.067v93.867     c-23.525,0-42.667,19.142-42.667,42.667v187.733c0,23.525,19.142,42.667,42.667,42.667h290.133     c23.525,0,42.667-19.142,42.667-42.667V281.6C443.733,258.075,424.592,238.933,401.067,238.933z M128,145.067     c0-70.579,57.421-128,128-128s128,57.421,128,128v93.867h-25.6v-93.867c0-56.462-45.938-102.4-102.4-102.4     c-56.462,0-102.4,45.938-102.4,102.4v93.867H128V145.067z M341.333,145.067v93.867H170.667v-93.867     c0-47.054,38.279-85.333,85.333-85.333C303.054,59.733,341.333,98.013,341.333,145.067z M426.667,469.333     c0,14.117-11.483,25.6-25.6,25.6H110.933c-14.117,0-25.6-11.483-25.6-25.6V281.6c0-14.117,11.483-25.6,25.6-25.6h8.533h42.667     h187.733h42.667h8.533c14.117,0,25.6,11.483,25.6,25.6V469.333z"/><path d="M256,307.2c-23.525,0-42.667,19.142-42.667,42.667c0,13.483,6.429,26.133,17.067,34.104v34.163     c0,14.117,11.483,25.6,25.6,25.6s25.6-11.483,25.6-25.6v-34.163c10.637-7.971,17.067-20.621,17.067-34.104     C298.667,326.342,279.525,307.2,256,307.2z M268.812,371.992c-2.646,1.525-4.279,4.346-4.279,7.4v38.742     c0,4.704-3.829,8.533-8.533,8.533s-8.533-3.829-8.533-8.533v-38.742c0-3.054-1.633-5.875-4.279-7.4     c-7.888-4.533-12.788-13.008-12.788-22.125c0-14.117,11.483-25.6,25.6-25.6s25.6,11.483,25.6,25.6     C281.6,358.983,276.7,367.458,268.812,371.992z"/></g></g></g></svg>
                                        {{ trans('labutton.lock') }}
                                    </a>
                                    <a class="dropdown-item p-1" onclick="lockCourse(0,0)" data-status="0" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 507.376 507.376" height="512" viewBox="0 0 507.376 507.376" width="512"><g><g><path d="m422.714 507.376h-338.052c-11.66 0-21.141-9.48-21.141-21.141v-252.036c0-11.66 9.48-21.141 21.141-21.141h338.052c11.66 0 21.141 9.48 21.141 21.141v252.036c-.001 11.66-9.481 21.141-21.141 21.141zm-338.052-278.462c-2.907 0-5.285 2.378-5.285 5.285v252.036c0 2.907 2.378 5.285 5.285 5.285h338.052c2.907 0 5.285-2.378 5.285-5.285v-252.036c0-2.907-2.378-5.285-5.285-5.285z"/><g><path d="m270.798 429.75h-34.221c-4.393 0-7.928-3.534-7.928-7.928v-50.638c-11.892-8.192-19.126-21.702-19.126-36.368 0-24.345 19.819-44.164 44.164-44.164s44.164 19.819 44.164 44.164c0 14.666-7.234 28.177-19.126 36.368v50.638c.001 4.393-3.566 7.928-7.927 7.928zm-26.293-15.856h18.366v-47.137c0-2.907 1.619-5.615 4.195-7.003 9.216-4.922 14.931-14.501 14.931-24.939 0-15.591-12.684-28.276-28.309-28.276s-28.309 12.684-28.309 28.309c0 10.438 5.715 20.018 14.931 24.939 2.577 1.387 4.195 4.063 4.195 6.97z"/></g><path d="m383.372 228.914h-40.266c-4.393 0-7.928-3.534-7.928-7.928v-82.68c0-21.933-8.621-42.612-24.312-58.302-15.426-15.426-35.741-23.915-57.179-23.915-.033 0-.066 0-.099 0-44.858.066-81.358 36.6-81.358 81.491v26.294c0 15.459-12.585 28.077-28.077 28.077s-28.077-12.585-28.077-28.077v-26.294c0-18.564 3.634-36.6 10.835-53.578 6.937-16.384 16.846-31.116 29.465-43.735 12.618-12.618 27.351-22.561 43.735-29.465 16.977-7.168 35.013-10.802 53.577-10.802s36.6 3.634 53.578 10.835c16.384 6.937 31.116 16.846 43.735 29.465s22.528 27.351 29.465 43.735c7.168 16.979 10.835 35.014 10.835 53.578v83.373c-.001 4.36-3.535 7.928-7.929 7.928zm-32.338-15.856h24.411v-75.446c0-16.417-3.237-32.372-9.579-47.401-6.144-14.501-14.898-27.516-26.095-38.681-11.165-11.165-24.213-19.951-38.714-26.095-14.997-6.342-30.951-9.579-47.368-9.579s-32.372 3.237-47.401 9.579c-14.501 6.144-27.516 14.898-38.681 26.095-11.165 11.165-19.951 24.18-26.095 38.681-6.342 15.03-9.579 30.951-9.579 47.401v26.294c0 6.739 5.483 12.222 12.222 12.222s12.222-5.483 12.222-12.222v-26.294c0-53.611 43.603-97.28 97.214-97.346h.132c25.666 0 49.945 10.141 68.377 28.54 9.051 9.051 16.186 19.588 21.174 31.315 5.186 12.156 7.796 25.005 7.796 38.185z"/></g></g></svg>
                                        {{ trans('labutton.open') }}
                                    </a>
                                    <a class="dropdown-item p-1" id="send-mail-approve" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512"><path d="m478.5 83.5h-385c-18.472 0-33.5 15.028-33.5 33.5v71.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5h90c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-22.5v-71.5c0-2.342.455-4.576 1.253-6.64l145.64 145.64-145.64 145.64c-.798-2.064-1.253-4.298-1.253-6.64v-49c0-4.143-3.358-7.5-7.5-7.5s-7.5 3.357-7.5 7.5v49c0 18.472 15.028 33.5 33.5 33.5h385c18.472 0 33.5-15.028 33.5-33.5v-278c0-18.472-15.028-33.5-33.5-33.5zm-128.393 172.5 145.64-145.64c.798 2.064 1.253 4.298 1.253 6.64v278c0 2.342-.455 4.576-1.253 6.64zm128.393-157.5c2.342 0 4.576.455 6.64 1.253l-167.32 167.32c-17.545 17.547-46.094 17.547-63.64 0l-167.32-167.32c2.064-.798 4.298-1.253 6.64-1.253zm-385 315c-2.342 0-4.576-.455-6.64-1.253l145.64-145.64 11.074 11.074c11.697 11.696 27.062 17.545 42.427 17.545s30.729-5.849 42.426-17.545l11.074-11.074 145.64 145.64c-2.064.798-4.298 1.253-6.64 1.253z"/><path d="m67.5 218.5c-4.142 0-7.5 3.357-7.5 7.5v22.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5h120c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-52.5v-22.5c0-4.143-3.358-7.5-7.5-7.5z"/><path d="m97.5 323.5c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-22.5v-22.5c0-4.143-3.358-7.5-7.5-7.5s-7.5 3.357-7.5 7.5v22.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5z"/></svg> {{ trans('labutton.send_mail_approve') }}
                                    </a>
                                    <a class="dropdown-item p-1" id="send-mail-change" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512"><path d="m478.5 83.5h-385c-18.472 0-33.5 15.028-33.5 33.5v71.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5h90c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-22.5v-71.5c0-2.342.455-4.576 1.253-6.64l145.64 145.64-145.64 145.64c-.798-2.064-1.253-4.298-1.253-6.64v-49c0-4.143-3.358-7.5-7.5-7.5s-7.5 3.357-7.5 7.5v49c0 18.472 15.028 33.5 33.5 33.5h385c18.472 0 33.5-15.028 33.5-33.5v-278c0-18.472-15.028-33.5-33.5-33.5zm-128.393 172.5 145.64-145.64c.798 2.064 1.253 4.298 1.253 6.64v278c0 2.342-.455 4.576-1.253 6.64zm128.393-157.5c2.342 0 4.576.455 6.64 1.253l-167.32 167.32c-17.545 17.547-46.094 17.547-63.64 0l-167.32-167.32c2.064-.798 4.298-1.253 6.64-1.253zm-385 315c-2.342 0-4.576-.455-6.64-1.253l145.64-145.64 11.074 11.074c11.697 11.696 27.062 17.545 42.427 17.545s30.729-5.849 42.426-17.545l11.074-11.074 145.64 145.64c-2.064.798-4.298 1.253-6.64 1.253z"/><path d="m67.5 218.5c-4.142 0-7.5 3.357-7.5 7.5v22.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5h120c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-52.5v-22.5c0-4.143-3.358-7.5-7.5-7.5z"/><path d="m97.5 323.5c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-22.5v-22.5c0-4.143-3.358-7.5-7.5-7.5s-7.5 3.357-7.5 7.5v22.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5z"/></svg> {{ trans('labutton.send_mail_change') }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                        @can('offline-course-create')
                            <a href="{{ route('module.offline.create') }}" class="btn"><i class="fa fa-plus-circle"></i>
                                {{ trans('labutton.add_new') }}
                            </a>
                        @endcan
                        @can('offline-course-delete')
                        <button class="btn" id="delete-item"><i class="fa fa-trash"></i>
                            {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    <table class="tDefault table table-hover bootstrap-table">
        <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-align="center" data-formatter="isopen_formatter" data-width="3%">{{ trans('latraining.open_off') }}</th>
                <th data-field="lock_course" data-align="center" data-formatter="lock_formatter" data-width="5%">{{ trans('latraining.lock') }}</th>
                <th data-formatter="name_formatter" class="text-nowrap">{{ trans('latraining.course') }}</th>
                <th data-align="center" data-formatter="convert_course_plan_formatter" data-width="5%">{{ trans('backend.created_by') }}</th>
                <th data-align="center" data-formatter="action_plan_formatter" data-width="5%">{{ trans('latraining.plan') }}</th>
                <th data-field="subject_name" data-width="20%">{{ trans('latraining.subject') }}</th>
                <th data-field="register_deadline" data-align="center" data-width="5%">{{ trans('latraining.register_deadline') }}</th>
                <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.status') }}</th>
                <th data-formatter="info_formatter" data-align="center" data-width="5%">{{ trans('latraining.info') }}</th>
            </tr>
        </thead>
    </table>

    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modal-import" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-import">{{ trans('laprofile.import') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-7 control-label"> {{ trans('latraining.register') }}</div>
                        <div class="col-md-5">
                            <button class="btn" id="import-register" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                            </button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-7 control-label"> {{ trans('latraining.result') }}</div>
                        <div class="col-md-5">
                            <button class="btn" id="import-result" type="submit" name="task" value="import">
                                <i class="fa fa-upload"></i> {{ trans('labutton.import') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-template-import" tabindex="-1" role="dialog" aria-labelledby="modal-template-import" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-template-import">{{ trans('laprofile.import_template') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-7 control-label"> {{ trans('latraining.register') }}</div>
                        <div class="col-md-5">
                            <a class="btn" href="{{ download_template('mau_import_nhan_vien_ghi_danh_nhieu_khoa_hoc_offline.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-7 control-label"> {{ trans('latraining.result') }}</div>
                        <div class="col-md-5">
                            <a class="btn" href="{{ download_template('mau_import_ket_qua_dao_tao_nhieu_khoa_hoc.xlsx') }}"><i class="fa fa-download"></i> {{ trans('labutton.import_template') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--  Import ghi danh nhiều khoá học  --}}
    <div class="modal fade" id="modal-import-register" tabindex="-1" role="dialog" aria-labelledby="modal-import-register" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.offline.import_register_multiple_course') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-import-register">{{ (trans('latraining.register')) }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{--  Import kết quả nhiều khoá học  --}}
    <div class="modal fade" id="modal-import-result" tabindex="-1" role="dialog" aria-labelledby="modal-import-result" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.offline.import_result_multiple_course') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-import-result">{{ (trans('latraining.result')) }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function name_formatter(value, row, index) {
        return '<a href="' + row.edit_url + '">' + row.name + '<br> (' + row.code + ') </a> <br>'+ row.start_date + (row.end_date ? ' <i class="fa fa-arrow-right"></i> ' + row.end_date : ' ');
    }

    function isopen_formatter(value, row, index) {
        var status = row.isopen == 1 ? 'checked' : '';
        var html = `<div class="custom-control custom-switch">
                        <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                        <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                    </div>`;
        return html;
    }

    function action_plan_formatter(value, row, index) {
        return (row.in_plan) ? '{{ trans("latraining.yes") }}' : '{{ trans("latraining.no") }}';
    }

    function convert_course_plan_formatter(value, row, index) {
        return (row.convert_course_plan == 1) ? '{{ trans("latraining.proposed_plan") }}' : '{{ trans("latraining.training_center") }}';
    }

    function status_formatter(value, row, index) {
        var text_status = '';
        if(row.status == 2 || row.status == 0) {
            value = parseInt(value);
            switch (value) {
                case 0: text_status = '<span class="text-danger">{{ trans("latraining.deny") }}</span>'; break;
                case 2: text_status = '<span class="text-warning">{{ trans("latraining.not_approved") }}</span>'; break;
            }
        } else {
            text_status = '<span class="text-danger">'+ row.status_approve +'</span>';
        }

        if(row.approved_step && value != 2){
            text_status += `<br> (<a href="javascript:void(0)" data-parent_unit="${row.parent_unit}" data-id="${row.id}" data-model="el_offline_course" class="text-success font-weight-bold load-modal-approved-step">${row.approved_step}</a>)`;
        } else if (value == 2) {
            text_status += `<br> (<a href="javascript:void(0)" data-parent_unit="${row.parent_unit}" data-id="${row.id}" data-model="el_offline_course" class="text-success font-weight-bold load-modal-approved-step">0/`+ row.count_level_permission_approve +`</a>)`;
        }

        return text_status;
    }

    function lock_formatter(value, row, index) {
        value = parseInt(value);
        switch (value) {
            case 0:
                return '<a style="cursor: pointer;" onclick=lockCourse('+row.id+',1)> <i class="fa fa-lock-open"></i></a>';
            case 1:
                return '<a style="cursor: pointer;" onclick=lockCourse('+row.id+',0)> <i class="fa fa-lock"></i></a>';
        }
    }

    function info_formatter(value, row, index) {
        var register = '';
        @can('offline-course-register')
            if(row.check_class > 1){
                register = '<a href="javascript:void(0)" class="load-modal btn mb-1" data-url="'+row.register_class_url+'" title="{{ trans('latraining.register') }}"><i class="fa fa-user"></i></a>';
            }else{
                register = '<a href="' + row.register_url + '" class="btn mb-1" title="{{ trans('latraining.register') }}"><i class="fa fa-user"></i></a>';
            }
        @endcan

        register += '<a href="javascript:void(0)" class="load-modal btn" data-url="'+row.info_url+'" title="{{ trans('latraining.info') }}"><i class="fa fa-info-circle"></i></a>';

        return register;
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.offline.getdata') }}',
        remove_url: '{{ route('module.offline.remove') }}'
    });

    $('#training_program').on('change', function () {
        var training_program_id = $('#training_program option:selected').val();
        $("#level_subject").empty();
        $("#level_subject").data('training-program', training_program_id);
        $('#level_subject').trigger('change');
    });

    $('#level_subject').on('change', function () {
        var training_program_id = $('#training_program option:selected').val();
        var level_subject_id = $('#level_subject option:selected').val();
        $("#subject").empty();
        $("#subject").data('training-program', training_program_id);
        $("#subject").data('level-subject', level_subject_id);
        $('#subject').trigger('change');
    });

    var ajax_isopen_publish = "{{ route('module.offline.ajax_isopen_publish') }}";

    function changeStatus(id,status) {
        let item = $('#dropdownMenuButton');
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i>');

        if (id && !status) {
            var ids = id;
            var checked = $('#customSwitch_' + id).is(":checked");
            var status = checked == true ? 1 : 0;
        } else {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('{{ trans("lacore.min_one_course") }}', 'error');
                item.html(oldtext);
                return false;
            }
        }
        item.prop("disabled", true);
        $.ajax({
            url: ajax_isopen_publish,
            type: 'post',
            data: {
                ids: ids,
                status: status
            }
        }).done(function(data) {
            item.html(oldtext);
            item.prop("disabled", false);

            if (id == 0) {
                show_message(data.message, data.status);
            }
            $(table.table).bootstrapTable('refresh');
            $('.btn_action_table').toggle(false);
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    };

    function lockCourse(id,status) {
        let item = $('#dropdownMenuButton');
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i>');

        if (id) {
            var ids = id;
        } else {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('{{ trans("lacore.min_one_course") }}', 'error');
                item.html(oldtext);
                return false;
            }
        }
        item.prop("disabled", true);
        $.ajax({
            url: base_url +'/admin-cp/offline/lock',
            type: 'post',
            data: {
                ids: ids,
                status: status
            }
        }).done(function(data) {
            item.html(oldtext);
            item.prop("disabled", false);

            show_message(data.message, data.status);
            $(table.table).bootstrapTable('refresh');
            $('.btn_action_table').toggle(false);
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    };

    $('#model-list-import').on('click', function () {
        $('#modal-import').modal();
    });

    $('#model-list-template-import').on('click', function () {
        $('#modal-template-import').modal();
    });

    $('#import-register').on('click', function () {
        $('#modal-import').hide();
        $('#modal-import-register').modal();
    });

    $('#import-result').on('click', function () {
        $('#modal-import').hide();
        $('#modal-import-result').modal();
    });
</script>
<script src="{{ asset('styles/module/offline/js/offline.js?v='.time()) }}"></script>
{{-- @endsection --}}
