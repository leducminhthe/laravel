@extends('layouts.backend')

@section('page_title', trans('latraining.quiz_list'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('latraining.quiz_list'),
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-7">
                @include('quiz::backend.quiz.filter')
            </div>
            <div class="col-md-5 text-right act-btns" id="btn-quiz">
                <div class="pull-right">
                    <div class="btn-group">
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                                {{ trans('labutton.task') }}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="min-width: 13rem;">
                                @can('quiz-copy')
                                    <a class="dropdown-item p-1 copy" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 60 60" width="512">
                                            <g id="Page-1" fill-rule="evenodd"><g id="134---Copy-Files" fill-rule="nonzero"><path id="Shape" d="m29 6h6c.5522847 0 1-.44771525 1-1s-.4477153-1-1-1h-6c-.5522847 0-1 .44771525-1 1s.4477153 1 1 1z"/><path id="Shape" d="m39 6h2c.5522847 0 1-.44771525 1-1s-.4477153-1-1-1h-2c-.5522847 0-1 .44771525-1 1s.4477153 1 1 1z"/><path id="Shape" d="m9 20h9c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-9c-.55228475 0-1 .4477153-1 1s.44771525 1 1 1z"/><path id="Shape" d="m23 20h15c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-15c-.5522847 0-1 .4477153-1 1s.4477153 1 1 1z"/><path id="Shape" d="m38 23h-9c-.5522847 0-1 .4477153-1 1s.4477153 1 1 1h9c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1z"/><path id="Shape" d="m9 25h15c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-15c-.55228475 0-1 .4477153-1 1s.44771525 1 1 1z"/><path id="Shape" d="m19 29c0-.5522847-.4477153-1-1-1h-9c-.55228475 0-1 .4477153-1 1s.44771525 1 1 1h9c.5522847 0 1-.4477153 1-1z"/><path id="Shape" d="m38 28h-15c-.5522847 0-1 .4477153-1 1s.4477153 1 1 1h15c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1z"/><path id="Shape" d="m55 4h-9.1c-.4784327-2.3264168-2.5248992-3.99700171-4.9-4h-27c-.2651948.00005664-.5195073.10545063-.707.293l-13 13c-.18754937.1874927-.29294336.4418052-.293.707v37c.00330612 2.7600532 2.23994685 4.9966939 5 5h9.1c.4784327 2.3264168 2.5248992 3.9970017 4.9 4h36c2.7600532-.0033061 4.9966939-2.2399468 5-5v-46c-.0033061-2.76005315-2.2399468-4.99669388-5-5zm-13 44v-.5c0-.8284271-.6715729-1.5-1.5-1.5h-10.5v-4h10.5c.8284271 0 1.5-.6715729 1.5-1.5v-.5l5.334 4zm-29-44.586v6.586c0 1.6568542-1.3431458 3-3 3h-6.586zm-11 47.586v-36h8c2.7600532-.0033061 4.9966939-2.2399468 5-5v-8h26c1.6568542 0 3 1.34314575 3 3v34l-1.6-1.2c-.4545265-.3408949-1.0626444-.3957288-1.5708204-.1416408s-.8291796.7734827-.8291796 1.3416408v1h-10.5c-.8284271 0-1.5.6715729-1.5 1.5v5c0 .8284271.6715729 1.5 1.5 1.5h10.5v1c0 .5681581.3210036 1.0875528.8291796 1.3416408s1.1162939.1992541 1.5708204-.1416408l1.6-1.2v2c0 1.6568542-1.3431458 3-3 3h-36c-1.65685425 0-3-1.3431458-3-3zm56 4c0 1.6568542-1.3431458 3-3 3h-36c-1.2667854-.0052514-2.3937454-.8056491-2.816-2h24.816c2.7600532-.0033061 4.9966939-2.2399468 5-5v-3.5l3.067-2.3c.3777088-.2832816.6-.727864.6-1.2s-.2222912-.9167184-.6-1.2l-3.067-2.3v-6.5h6c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-6v-3h6c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-6v-3h6c.5522847 0 1-.4477153 1-1s-.4477153-1-1-1h-6v-12h3c.5522847 0 1-.44771525 1-1s-.4477153-1-1-1h-3v-2h9c1.6568542 0 3 1.34314575 3 3z"/><path id="Shape" d="m55 8h-2c-.5522847 0-1 .44771525-1 1s.4477153 1 1 1h2c.5522847 0 1-.44771525 1-1s-.4477153-1-1-1z"/></g></g>
                                        </svg> {{ trans('labutton.copy') }}
                                    </a>
                                @endcan
                                @can('quiz-status')
                                    <a class="dropdown-item p-1" onclick="changeStatus(0,1)" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                            <g>
                                                <g>
                                                    <path d="M373.333,160c-52.928,0-96,43.072-96,96s43.072,96,96,96c52.928,0,96-43.072,96-96S426.261,160,373.333,160z     M373.333,330.667c-41.173,0-74.667-33.493-74.667-74.667s33.493-74.667,74.667-74.667C414.507,181.333,448,214.827,448,256    S414.507,330.667,373.333,330.667z" />
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M373.333,117.333H138.667C62.208,117.333,0,179.541,0,256s62.208,138.667,138.667,138.667h234.667    C449.792,394.667,512,332.459,512,256S449.792,117.333,373.333,117.333z M373.333,373.333H138.667    c-64.683,0-117.333-52.629-117.333-117.333s52.651-117.333,117.333-117.333h234.667c64.683,0,117.333,52.629,117.333,117.333    S438.016,373.333,373.333,373.333z" />
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M117.333,202.667c-17.643,0-32,14.357-32,32v42.667c0,17.643,14.357,32,32,32c17.643,0,32-14.357,32-32v-42.667    C149.333,217.024,134.976,202.667,117.333,202.667z M128,277.333c0,5.888-4.8,10.667-10.667,10.667    c-5.867,0-10.667-4.779-10.667-10.667v-42.667c0-5.888,4.8-10.667,10.667-10.667C123.2,224,128,228.779,128,234.667V277.333z" />
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M224,202.667c-5.888,0-10.667,4.779-10.667,10.667v40.149l-22.443-44.928c-2.219-4.416-7.104-6.763-12.011-5.611    c-4.821,1.131-8.213,5.44-8.213,10.389v85.333c0,5.888,4.779,10.667,10.667,10.667S192,304.555,192,298.667v-40.149l22.464,44.928    c1.835,3.669,5.547,5.888,9.536,5.888c0.811,0,1.621-0.085,2.453-0.277c4.821-1.131,8.213-5.44,8.213-10.389v-85.333    C234.667,207.445,229.888,202.667,224,202.667z" />
                                                </g>
                                            </g>
                                        </svg> {{ trans('labutton.enable') }}
                                    </a>
                                    <a class="dropdown-item p-1" onclick="changeStatus(0,0)" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                            <g>
                                                <g>
                                                    <path d="M138.667,160c-52.928,0-96,43.072-96,96s43.072,96,96,96c52.928,0,96-43.072,96-96S191.595,160,138.667,160z     M138.667,330.667C97.493,330.667,64,297.173,64,256s33.493-74.667,74.667-74.667s74.667,33.493,74.667,74.667    S179.84,330.667,138.667,330.667z"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M373.333,117.333H138.667C62.208,117.333,0,179.541,0,256s62.208,138.667,138.667,138.667h234.667    C449.792,394.667,512,332.459,512,256S449.792,117.333,373.333,117.333z M373.333,373.333H138.667    c-64.683,0-117.333-52.629-117.333-117.333s52.651-117.333,117.333-117.333h234.667c64.683,0,117.333,52.629,117.333,117.333    S438.016,373.333,373.333,373.333z"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M288,202.667c-17.643,0-32,14.357-32,32v42.667c0,17.643,14.357,32,32,32s32-14.357,32-32v-42.667    C320,217.024,305.643,202.667,288,202.667z M298.667,277.333c0,5.888-4.8,10.667-10.667,10.667s-10.667-4.779-10.667-10.667    v-42.667c0-5.888,4.8-10.667,10.667-10.667s10.667,4.779,10.667,10.667V277.333z"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M384,202.667h-32c-5.888,0-10.667,4.779-10.667,10.667v85.333c0,5.888,4.779,10.667,10.667,10.667    c5.888,0,10.667-4.779,10.667-10.667V224H384c5.888,0,10.667-4.779,10.667-10.667S389.888,202.667,384,202.667z"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M373.333,245.333H352c-5.888,0-10.667,4.779-10.667,10.667s4.779,10.667,10.667,10.667h21.333    c5.888,0,10.667-4.779,10.667-10.667S379.221,245.333,373.333,245.333z"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M448,202.667h-32c-5.888,0-10.667,4.779-10.667,10.667v85.333c0,5.888,4.779,10.667,10.667,10.667    c5.888,0,10.667-4.779,10.667-10.667V224H448c5.888,0,10.667-4.779,10.667-10.667S453.888,202.667,448,202.667z"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M437.333,245.333H416c-5.888,0-10.667,4.779-10.667,10.667s4.779,10.667,10.667,10.667h21.333    c5.888,0,10.667-4.779,10.667-10.667S443.221,245.333,437.333,245.333z"/>
                                                </g>
                                            </g>
                                        </svg> {{ trans('labutton.disable') }}
                                    </a>
                                @endcan
                                @can('quiz-approve')
                                    <a class="dropdown-item p-1 approved" data-model="el_quiz" data-status="1" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Icons" enable-background="new 0 0 128 128" height="512" viewBox="0 0 128 128" width="512"><path id="Check_Mark" d="m64 128c-35.289 0-64-28.711-64-64s28.711-64 64-64 64 28.711 64 64-28.711 64-64 64zm0-120c-30.879 0-56 25.121-56 56s25.121 56 56 56 56-25.121 56-56-25.121-56-56-56zm-9.172 78.828 40-40c1.563-1.563 1.563-4.094 0-5.656s-4.094-1.563-5.656 0l-37.172 37.172-13.172-13.172c-1.563-1.563-4.094-1.563-5.656 0s-1.563 4.094 0 5.656l16 16c.781.781 1.805 1.172 2.828 1.172s2.047-.391 2.828-1.172z"/></svg> {{ trans('labutton.approve') }}
                                    </a>
                                    <a class="dropdown-item p-1 approved" data-model="el_quiz" data-status="0" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 16 16" width="512"><g id="_19" data-name="19"><path d="m8 16a8 8 0 1 1 8-8 8 8 0 0 1 -8 8zm0-15a7 7 0 1 0 7 7 7 7 0 0 0 -7-7z"/><path d="m8.71 8 3.14-3.15a.49.49 0 0 0 -.7-.7l-3.15 3.14-3.15-3.14a.49.49 0 0 0 -.7.7l3.14 3.15-3.14 3.15a.48.48 0 0 0 0 .7.48.48 0 0 0 .7 0l3.15-3.14 3.15 3.14a.48.48 0 0 0 .7 0 .48.48 0 0 0 0-.7z"/></g></svg> {{trans('labutton.deny')}}
                                    </a>
                                @endcan
                                @can('quiz-view-result')
                                    <a class="dropdown-item p-1 result" data-status="1" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Icons" enable-background="new 0 0 128 128" height="512" viewBox="0 0 128 128" width="512"><path id="Show" d="m64 104c-41.873 0-62.633-36.504-63.496-38.057-.672-1.209-.672-2.678 0-3.887.863-1.552 21.623-38.056 63.496-38.056s62.633 36.504 63.496 38.057c.672 1.209.672 2.678 0 3.887-.863 1.552-21.623 38.056-63.496 38.056zm-55.293-40.006c4.758 7.211 23.439 32.006 55.293 32.006 31.955 0 50.553-24.775 55.293-31.994-4.758-7.211-23.439-32.006-55.293-32.006-31.955 0-50.553 24.775-55.293 31.994zm55.293 24.006c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm0-40c-8.822 0-16 7.178-16 16s7.178 16 16 16 16-7.178 16-16-7.178-16-16-16z"/></svg> {{trans("labutton.see_result")}}
                                    </a>
                                    <a class="dropdown-item p-1 result" data-status="0" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve">
                                            <g>
                                                <g>
                                                    <path d="M316.332,195.662c-4.16-4.16-10.923-4.16-15.083,0c-4.16,4.16-4.16,10.944,0,15.083    c12.075,12.075,18.752,28.139,18.752,45.248c0,35.285-28.715,64-64,64c-17.109,0-33.173-6.656-45.248-18.752    c-4.16-4.16-10.923-4.16-15.083,0c-4.16,4.139-4.16,10.923,0,15.083c16.085,16.128,37.525,25.003,60.331,25.003    c47.061,0,85.333-38.272,85.333-85.333C341.334,233.187,332.46,211.747,316.332,195.662z"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M270.87,172.131c-4.843-0.853-9.792-1.472-14.869-1.472c-47.061,0-85.333,38.272-85.333,85.333    c0,5.077,0.619,10.027,1.493,14.869c0.917,5.163,5.419,8.811,10.475,8.811c0.619,0,1.237-0.043,1.877-0.171    c5.781-1.024,9.664-6.571,8.64-12.352c-0.661-3.627-1.152-7.317-1.152-11.157c0-35.285,28.715-64,64-64    c3.84,0,7.531,0.491,11.157,1.131c5.675,1.152,11.328-2.859,12.352-8.64C280.534,178.702,276.652,173.155,270.87,172.131z"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M509.462,249.102c-2.411-2.859-60.117-70.208-139.712-111.445c-5.163-2.709-11.669-0.661-14.379,4.587    c-2.709,5.227-0.661,11.669,4.587,14.379c61.312,31.744,110.293,81.28,127.04,99.371c-25.429,27.541-125.504,128-230.997,128    c-35.797,0-71.872-8.64-107.264-25.707c-5.248-2.581-11.669-0.341-14.229,4.971c-2.581,5.291-0.341,11.669,4.971,14.229    c38.293,18.496,77.504,27.84,116.523,27.84c131.435,0,248.555-136.619,253.483-142.443    C512.854,258.915,512.833,253.091,509.462,249.102z"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M325.996,118.947c-24.277-8.171-47.829-12.288-69.995-12.288c-131.435,0-248.555,136.619-253.483,142.443    c-3.115,3.669-3.371,9.003-0.597,12.992c1.472,2.112,36.736,52.181,97.856,92.779c1.813,1.216,3.84,1.792,5.888,1.792    c3.435,0,6.827-1.664,8.875-4.8c3.264-4.885,1.92-11.52-2.987-14.763c-44.885-29.845-75.605-65.877-87.104-80.533    c24.555-26.667,125.291-128.576,231.552-128.576c19.861,0,41.131,3.755,63.189,11.157c5.589,2.005,11.648-1.088,13.504-6.699    C334.572,126.862,331.585,120.825,325.996,118.947z"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M444.865,67.128c-4.16-4.16-10.923-4.16-15.083,0L67.116,429.795c-4.16,4.16-4.16,10.923,0,15.083    c2.091,2.069,4.821,3.115,7.552,3.115c2.731,0,5.461-1.045,7.531-3.115L444.865,82.211    C449.025,78.051,449.025,71.288,444.865,67.128z"/>
                                                </g>
                                            </g>
                                        </svg> {{trans("labutton.off_result")}}
                                    </a>
                                @endcan
                                @canany(['quiz-create', 'quiz-edit'])
                                    <a class="dropdown-item p-1" id="send-mail-approve" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512"><path d="m478.5 83.5h-385c-18.472 0-33.5 15.028-33.5 33.5v71.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5h90c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-22.5v-71.5c0-2.342.455-4.576 1.253-6.64l145.64 145.64-145.64 145.64c-.798-2.064-1.253-4.298-1.253-6.64v-49c0-4.143-3.358-7.5-7.5-7.5s-7.5 3.357-7.5 7.5v49c0 18.472 15.028 33.5 33.5 33.5h385c18.472 0 33.5-15.028 33.5-33.5v-278c0-18.472-15.028-33.5-33.5-33.5zm-128.393 172.5 145.64-145.64c.798 2.064 1.253 4.298 1.253 6.64v278c0 2.342-.455 4.576-1.253 6.64zm128.393-157.5c2.342 0 4.576.455 6.64 1.253l-167.32 167.32c-17.545 17.547-46.094 17.547-63.64 0l-167.32-167.32c2.064-.798 4.298-1.253 6.64-1.253zm-385 315c-2.342 0-4.576-.455-6.64-1.253l145.64-145.64 11.074 11.074c11.697 11.696 27.062 17.545 42.427 17.545s30.729-5.849 42.426-17.545l11.074-11.074 145.64 145.64c-2.064.798-4.298 1.253-6.64 1.253z"/><path d="m67.5 218.5c-4.142 0-7.5 3.357-7.5 7.5v22.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5h120c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-52.5v-22.5c0-4.143-3.358-7.5-7.5-7.5z"/><path d="m97.5 323.5c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-22.5v-22.5c0-4.143-3.358-7.5-7.5-7.5s-7.5 3.357-7.5 7.5v22.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5z"/></svg> {{trans("labutton.send_mail_approve")}}
                                    </a>
                                    <a class="dropdown-item p-1" id="send-mail-change" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512"><path d="m478.5 83.5h-385c-18.472 0-33.5 15.028-33.5 33.5v71.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5h90c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-22.5v-71.5c0-2.342.455-4.576 1.253-6.64l145.64 145.64-145.64 145.64c-.798-2.064-1.253-4.298-1.253-6.64v-49c0-4.143-3.358-7.5-7.5-7.5s-7.5 3.357-7.5 7.5v49c0 18.472 15.028 33.5 33.5 33.5h385c18.472 0 33.5-15.028 33.5-33.5v-278c0-18.472-15.028-33.5-33.5-33.5zm-128.393 172.5 145.64-145.64c.798 2.064 1.253 4.298 1.253 6.64v278c0 2.342-.455 4.576-1.253 6.64zm128.393-157.5c2.342 0 4.576.455 6.64 1.253l-167.32 167.32c-17.545 17.547-46.094 17.547-63.64 0l-167.32-167.32c2.064-.798 4.298-1.253 6.64-1.253zm-385 315c-2.342 0-4.576-.455-6.64-1.253l145.64-145.64 11.074 11.074c11.697 11.696 27.062 17.545 42.427 17.545s30.729-5.849 42.426-17.545l11.074-11.074 145.64 145.64c-2.064.798-4.298 1.253-6.64 1.253z"/><path d="m67.5 218.5c-4.142 0-7.5 3.357-7.5 7.5v22.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5h120c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-52.5v-22.5c0-4.143-3.358-7.5-7.5-7.5z"/><path d="m97.5 323.5c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-22.5v-22.5c0-4.143-3.358-7.5-7.5-7.5s-7.5 3.357-7.5 7.5v22.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5z"/></svg> {{trans("labutton.send_mail_change")}}
                                    </a>
                                    <a class="dropdown-item p-1" id="send-mail-invitation" style="cursor: pointer;">
                                        <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512"><path d="m478.5 83.5h-385c-18.472 0-33.5 15.028-33.5 33.5v71.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5h90c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-22.5v-71.5c0-2.342.455-4.576 1.253-6.64l145.64 145.64-145.64 145.64c-.798-2.064-1.253-4.298-1.253-6.64v-49c0-4.143-3.358-7.5-7.5-7.5s-7.5 3.357-7.5 7.5v49c0 18.472 15.028 33.5 33.5 33.5h385c18.472 0 33.5-15.028 33.5-33.5v-278c0-18.472-15.028-33.5-33.5-33.5zm-128.393 172.5 145.64-145.64c.798 2.064 1.253 4.298 1.253 6.64v278c0 2.342-.455 4.576-1.253 6.64zm128.393-157.5c2.342 0 4.576.455 6.64 1.253l-167.32 167.32c-17.545 17.547-46.094 17.547-63.64 0l-167.32-167.32c2.064-.798 4.298-1.253 6.64-1.253zm-385 315c-2.342 0-4.576-.455-6.64-1.253l145.64-145.64 11.074 11.074c11.697 11.696 27.062 17.545 42.427 17.545s30.729-5.849 42.426-17.545l11.074-11.074 145.64 145.64c-2.064.798-4.298 1.253-6.64 1.253z"/><path d="m67.5 218.5c-4.142 0-7.5 3.357-7.5 7.5v22.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5h120c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-52.5v-22.5c0-4.143-3.358-7.5-7.5-7.5z"/><path d="m97.5 323.5c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-22.5v-22.5c0-4.143-3.358-7.5-7.5-7.5s-7.5 3.357-7.5 7.5v22.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5z"/></svg> {{trans("labutton.send_mail_invite")}}
                                    </a>
                                @endcanany
                            </div>
                        </div>
                        @can('quiz-create')
                        <a href="{{ route('module.quiz.create') }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                        @endcan
                        @can('quiz-delete')
                            <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table text-nowrap">
            <thead>
                <tr>
                    <th data-field="state" data-width="1%" data-checkbox="true"></th>
                    <th data-field="is_open" data-width="3%" data-formatter="is_open_formatter" data-align="center">{{ trans('latraining.open_off') }}</th>
                    <th data-field="name" data-formatter="name_formatter">{{trans('backend.quiz')}}</th>
                    <th data-field="quiz_type_text" data-width="7%" data-align="center">{{trans('backend.quiz_form')}}</th>
                    <th data-field="limit_time" data-width="5%" data-align="center" data-formatter="limit_time_formatter">
                        {{trans('backend.time')}} <br> {{trans('backend.do_quiz')}}
                    </th>
                    <th data-field="view_result" data-formatter="view_result_formatter" data-align="center" data-width="7%">{{trans('backend.see_result')}}</th>
                    <th data-field="regist" data-align="center" data-formatter="register_formatter" data-width="10%">{{trans('backend.action')}}</th>
                    <th data-field="quantity_quiz_attempts" data-width="7%" data-align="center" data-formatter="number_candidates_submission">
                        {{ trans('backend.number_candidates_submission') }}
                    </th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            html = `<a href="`+ row.edit_url +`">`+ row.name +`</a>
                    <br />
                    <span style="font-size: 13px">(`+ row.code +`)</span>
                    <br />
                    `+ (row.start_date ? row.start_date + (row.end_date ? '<i class="fa fa-arrow-right"></i> ' + row.end_date : '') : '{{ trans("laquiz.no_part") }}');
            return html
        }

        function approved_formatter(value, row, index) {
            return value? `<a href="javascript:void(0)" data-id="${row.id}" data-model="el_quiz" class="text-success font-weight-bold load-modal-approved-step">${value}</a>`:'-';
        }
        function number_candidates_submission(value, row, index) {
            return row.quantity_quiz_attempts + ' / ' + row.quantity;
        }

        function limit_time_formatter(value, row, index) {
            return row.limit_time + " phút";
        }

        function status_formatter(value, row, index) {
            var text_status = '';
            value = parseInt(value);
            switch (value) {
                case 0: text_status = '<span class="text-danger">{{ trans("backend.deny") }}</span>'; break;
                case 1: text_status = '<span class="text-success">{{trans("backend.approve")}}</span>'; break;
                case 2 || null: text_status = '<span class="text-warning">{{ trans("backend.not_approved") }}</span>'; break;
            }

            if(row.approved_step && value != 2){
                text_status += `<br> (<a href="javascript:void(0)" data-parent_unit="${row.parent_unit}" data-id="${row.id}" data-model="el_quiz" class="text-success font-weight-bold load-modal-approved-step">${row.approved_step}</a>)`;
            } else if (value == 2) {
                text_status += `<br> (<a href="javascript:void(0)" data-parent_unit="${row.parent_unit}" data-id="${row.id}" data-model="el_quiz" class="text-success font-weight-bold load-modal-approved-step">0/`+ row.count_level_permission_approve +`</a>)`;
            }

            return text_status;
        }

        function view_result_formatter(value, row, index) {
            return value == 1 ? '<i class="fa fa-eye text-success"></i>' : '<i class="fa fa-eye-slash text-danger"></i>';
        }

        function is_open_formatter(value, row, index) {
            var status = row.is_open == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        function register_formatter(value, row, index) {
            let str = '';
            if (row.register_url){
                str += '<a href="'+ row.register_url +'" class="btn mb-1"><i class="fa fa-users"></i> {{ trans("latraining.internally") }}</a>';
            }
            if (row.user_secondary_url && row.quiz_type == 3) {
                str += '<a href="'+ row.user_secondary_url +'" class="btn mb-1"><i class="fa fa-users"></i> {{ trans("latraining.outsides") }}</a>';
            }
            if (row.question) {
                str += ' <br> <a href="'+ row.question +'" class="btn" title="{{ trans("latraining.question") }}"><i class="fa fa-question-circle"></i></a>';
            }
            if (row.result){
                str += '<a href="'+ row.result +'" class="btn" title="{{ trans("latraining.result") }}"><i class="fa fa-eye"></i></a>';
            }
            if (row.export_url) {
                str += '<a href="'+ row.export_url +'" class="btn" title="In đề thi"><i class="fa fa-download"></i></a>';
            }
            if(row.modal_course_url){
                str += '<a href="javascript:void(0)" class="btn load-modal" data-url="'+row.modal_course_url+'" title="Thông tin Khóa học"> <i class="fa fa-book-open"></i></a>';
            }

            str += '<a href="javascript:void(0)" class="btn load-modal" data-url="'+row.info_url+'" title="Thông tin"> <i class="fa fa-info-circle"></i></a>';

            return str;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.getdata') }}',
            remove_url: '{{ route('module.quiz.remove') }}'
        });

        var ajax_isopen_publish = "{{ route('module.quiz.ajax_is_open') }}";
        var ajax_status = "{{ route('module.quiz.ajax_status') }}";
        var ajax_view_result = "{{ route('module.quiz.ajax_view_result') }}";
        var ajax_copy_quiz = "{{ route('module.quiz.ajax_copy_quiz') }}";

        // BẬT/TẮT
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
                    show_message('Vui lòng chọn 1 kỳ thi', 'error');
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
    </script>
    <script src="{{ asset('styles/module/quiz/js/quiz.js') }}"></script>
@endsection
