@extends('layouts.backend')

@section('page_title', trans('latraining.register'))

@section('breadcrumb')
    @php
        $breadcum= [
            [
                'name' => trans('lamenu.training_organizations'),
                'url' => ''
            ],
            [
                'name' => trans('lamenu.offline_course'),
                'url' => route('module.offline.management')
            ],
            [
                'name' => $course->name,
                'url' => route('module.offline.edit', ['id' => $course_id])
            ],
            /*[
                'name' => trans('latraining.classroom'),
                'url' => route('module.offline.class', ['id' => $course_id]),
                'drop-menu'=>$classArray
            ],*/
            [
                'name' => trans('latraining.registed_class') .': '.$class->name,
                'url' => ''
            ],
        ]
    @endphp
    @include('layouts.backend.menu_breadcum',$breadcum)
@endsection

@section('content')
<div role="main" class="form_offline_course">
    @if(isset($errors))
        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach
    @endif
        @include('offline::backend.includes.navgroup')
        <br>
        <div class="row">
            @if($course->lock_course == 0)
            <div class="col-md-12 act-btns">
                <div class="pull-right">
                    @include('offline::backend.register.filter_register')
                    <div class="wrraped_register text-right">
                        <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                                    {{ trans('labutton.task') }}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="min-width: 14rem;">
                                    @canany(['offline-course-register-approve'])
                                        @if(!$user_invited)
                                            <a class="dropdown-item p-1 approved" href="javascript:void(0)" data-model="el_offline_register" data-course_id="{{ $course_id }}" data-status="1" data-approve_all="1" data-class_id="{{ $class->id }}" style="cursor: pointer;">
                                                <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Icons" enable-background="new 0 0 128 128" height="512" viewBox="0 0 128 128" width="512"><path id="Check_Mark" d="m64 128c-35.289 0-64-28.711-64-64s28.711-64 64-64 64 28.711 64 64-28.711 64-64 64zm0-120c-30.879 0-56 25.121-56 56s25.121 56 56 56 56-25.121 56-56-25.121-56-56-56zm-9.172 78.828 40-40c1.563-1.563 1.563-4.094 0-5.656s-4.094-1.563-5.656 0l-37.172 37.172-13.172-13.172c-1.563-1.563-4.094-1.563-5.656 0s-1.563 4.094 0 5.656l16 16c.781.781 1.805 1.172 2.828 1.172s2.047-.391 2.828-1.172z"/></svg> {{ trans('laother.approve_all') }}
                                            </a>
                                            <a class="dropdown-item p-1 approved" href="javascript:void(0)" data-model="el_offline_register" data-course_id="{{ $course_id }}" data-status="1" style="cursor: pointer;">
                                                <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Icons" enable-background="new 0 0 128 128" height="512" viewBox="0 0 128 128" width="512"><path id="Check_Mark" d="m64 128c-35.289 0-64-28.711-64-64s28.711-64 64-64 64 28.711 64 64-28.711 64-64 64zm0-120c-30.879 0-56 25.121-56 56s25.121 56 56 56 56-25.121 56-56-25.121-56-56-56zm-9.172 78.828 40-40c1.563-1.563 1.563-4.094 0-5.656s-4.094-1.563-5.656 0l-37.172 37.172-13.172-13.172c-1.563-1.563-4.094-1.563-5.656 0s-1.563 4.094 0 5.656l16 16c.781.781 1.805 1.172 2.828 1.172s2.047-.391 2.828-1.172z"/></svg> {{ trans('labutton.approve') }}
                                            </a>
                                            <a class="dropdown-item p-1 approved" href="javascript:void(0)" data-model="el_offline_register" data-course_id="{{ $course_id }}" data-status="0" style="cursor: pointer;">
                                                <svg class="w_15" xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 16 16" width="512"><g id="_19" data-name="19"><path d="m8 16a8 8 0 1 1 8-8 8 8 0 0 1 -8 8zm0-15a7 7 0 1 0 7 7 7 7 0 0 0 -7-7z"/><path d="m8.71 8 3.14-3.15a.49.49 0 0 0 -.7-.7l-3.15 3.14-3.15-3.14a.49.49 0 0 0 -.7.7l3.14 3.15-3.14 3.15a.48.48 0 0 0 0 .7.48.48 0 0 0 .7 0l3.15-3.14 3.15 3.14a.48.48 0 0 0 .7 0 .48.48 0 0 0 0-.7z"/></g></svg> {{ trans('labutton.deny') }}
                                            </a>
                                        @endif
                                    @endcanany
                                    @canany(['offline-course-register-create'])
                                        <a class="dropdown-item p-1" href="{{ download_template('mau_import_nhan_vien_theo_username_ghi_danh_khoa_hoc.xlsx') }}">
                                            <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Layer_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512"><path d="m509.657 64.346-62.003-62.003c-1.5-1.5-3.535-2.343-5.657-2.343h-320.687c-4.418 0-8 3.582-8 8v181.459h-105.31c-4.418 0-8 3.582-8 8v128.32c0 4.418 3.582 8 8 8h105.31v170.221c0 4.418 3.582 8 8 8h382.69c4.418 0 8-3.582 8-8v-433.997c0-2.122-.843-4.157-2.343-5.657zm-59.66-37.033 34.689 34.689h-34.689zm-433.997 178.146h161.965c4.418 0 8-3.582 8-8v-35.909l115.091 100.069-115.091 100.069v-35.909c0-4.418-3.582-8-8-8h-161.965zm113.31 290.541v-162.221h40.655v45.466c0 3.135 1.831 5.98 4.683 7.28 1.062.483 2.192.72 3.315.72 1.894 0 3.766-.672 5.25-1.963l135.284-117.626c1.748-1.52 2.751-3.722 2.751-6.037s-1.003-4.518-2.751-6.037l-135.283-117.625c-2.366-2.057-5.714-2.542-8.566-1.243-2.853 1.3-4.683 4.146-4.683 7.28v45.466h-40.655v-173.46h304.687v54.003c0 4.418 3.582 8 8 8h54.003v417.997zm304.595-334.505c0 4.418-3.582 8-8 8h-180.005c-4.418 0-8-3.582-8-8s3.582-8 8-8h180.005c4.419 0 8 3.581 8 8zm0 133.499c0 4.418-3.582 8-8 8h-96.755c-4.418 0-8-3.582-8-8s3.582-8 8-8h96.755c4.419 0 8 3.582 8 8zm0-66.749c0 4.418-3.582 8-8 8h-96.755c-4.418 0-8-3.582-8-8s3.582-8 8-8h96.755c4.419 0 8 3.581 8 8zm0 133.499c0 4.418-3.582 8-8 8h-180.005c-4.418 0-8-3.582-8-8s3.582-8 8-8h180.005c4.419 0 8 3.582 8 8zm0 66.75c0 4.418-3.582 8-8 8h-226.5c-4.418 0-8-3.582-8-8s3.582-8 8-8h226.5c4.419 0 8 3.582 8 8zm-71.906-325.749h-162.594c-4.418 0-8-3.582-8-8s3.582-8 8-8h162.593c4.418 0 8 3.582 8 8s-3.581 8-7.999 8z"/></svg> {{ trans('labutton.import_template') }}
                                        </a>
                                        <a class="dropdown-item p-1" href="javascript:void(0)" id="import-plan" style="cursor: pointer;">
                                            <svg class="w_15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 410.8 410.8" style="enable-background:new 0 0 410.8 410.8;" xml:space="preserve">
                                                <g>
                                                    <g>
                                                        <g>
                                                            <path d="M333.4,138.8h-64c-4.4,0-8,3.6-8,8c0,4.4,3.6,8,8,8h64c13.2,0,24,10.8,24,24v192c0,13.2-10.8,24-24,24h-256     c-13.2,0-24-10.8-24-24v-192c0-13.2,10.8-24,24-24h72c4.4,0,8-3.6,8-8c0-4.4-3.6-8-8-8h-72c-22,0-40,18-40,40v192     c0,22,18,40,40,40h256c22,0,40-18,40-40v-192C373.4,156.8,355.4,138.8,333.4,138.8z"/>
                                                            <path d="M205.4,246.8c-4.4,0-8,3.6-8,8v12c0,4.4,3.6,8,8,8c4.4,0,8-3.6,8-8v-12C213.4,250.4,209.8,246.8,205.4,246.8z"/>
                                                            <path d="M140.2,84.4l57.2-57.2v191.6c0,4.4,3.6,8,8,8c4.4,0,8-3.6,8-8V27.2l57.2,57.2c1.6,1.6,3.6,2.4,5.6,2.4s4-0.8,5.6-2.4     c3.2-3.2,3.2-8,0-11.2L211,2.4c-3.2-3.2-8-3.2-11.2,0L129,73.2c-3.2,3.2-3.2,8,0,11.2C132.2,87.6,137,87.6,140.2,84.4z"/>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg> {{ trans('labutton.import') }}
                                        </a>
                                        <a class="dropdown-item p-1" href="{{ route('module.offline.register.export_register', ['id' => $course->id, 'class_id' => $class->id]) }}">
                                            <svg class="w_15" xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 24 24" width="512"><path d="m13 14.2929 2.1464-2.1465c.1953-.1952.5119-.1952.7072 0 .1952.1953.1952.5119 0 .7072l-3 3c-.1953.1952-.5119.1952-.7072 0l-2.99995-3c-.19527-.1953-.19527-.5119 0-.7072.19526-.1952.51184-.1952.7071 0l2.14645 2.1465v-10.7929c0-.27614.2239-.5.5-.5s.5.22386.5.5zm5.5-8.2929c-.2761 0-.5-.22386-.5-.5s.2239-.5.5-.5h1c1.3807 0 2.5 1.11929 2.5 2.5v10c0 1.3807-1.1193 2.5-2.5 2.5h-14c-1.38071 0-2.5-1.1193-2.5-2.5v-10c0-1.38071 1.11929-2.5 2.5-2.5h1c.27614 0 .5.22386.5.5s-.22386.5-.5.5h-1c-.82843 0-1.5.67157-1.5 1.5v10c0 .8284.67157 1.5 1.5 1.5h14c.8284 0 1.5-.6716 1.5-1.5v-10c0-.82843-.6716-1.5-1.5-1.5z"/></svg> {{ trans('labutton.export') }}
                                        </a>

                                        @if($course->quiz_id || $course->entrance_quiz_id)
                                            <a class="dropdown-item p-1" href="javascript:void(0)" id="add-to-quiz" style="cursor: pointer;">
                                                <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 189.524 189.524" height="512" viewBox="0 0 189.524 189.524" width="512"><g><g><path clip-rule="evenodd" d="m94.762 180.048c47.102 0 85.286-38.183 85.286-85.286 0-47.102-38.183-85.286-85.286-85.286-47.102 0-85.286 38.184-85.286 85.286s38.184 85.286 85.286 85.286zm0 9.476c52.335 0 94.762-42.427 94.762-94.762 0-52.336-42.427-94.762-94.762-94.762-52.336 0-94.762 42.426-94.762 94.762 0 52.335 42.426 94.762 94.762 94.762z" fill-rule="evenodd"/></g><g><path clip-rule="evenodd" d="m132.667 94.762c0 2.616-2.122 4.738-4.738 4.738h-66.334c-2.617 0-4.738-2.122-4.738-4.738s2.121-4.738 4.738-4.738h66.333c2.617 0 4.739 2.122 4.739 4.738z" fill-rule="evenodd"/></g><g><path clip-rule="evenodd" d="m94.762 132.667c-2.616 0-4.738-2.122-4.738-4.738v-66.334c0-2.617 2.122-4.738 4.738-4.738s4.738 2.121 4.738 4.738v66.333c0 2.617-2.122 4.739-4.738 4.739z" fill-rule="evenodd"/></g></g></svg>
                                                {{ trans('labutton.add_student_quiz') }}
                                            </a>
                                        @endif
                                        {{--  @if(!$user_invited)
                                            <a class="dropdown-item p-1" href="javascript:void(0)" id="invite-user-register" style="cursor: pointer;">
                                                <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 189.524 189.524" height="512" viewBox="0 0 189.524 189.524" width="512"><g><g><path clip-rule="evenodd" d="m94.762 180.048c47.102 0 85.286-38.183 85.286-85.286 0-47.102-38.183-85.286-85.286-85.286-47.102 0-85.286 38.184-85.286 85.286s38.184 85.286 85.286 85.286zm0 9.476c52.335 0 94.762-42.427 94.762-94.762 0-52.336-42.427-94.762-94.762-94.762-52.336 0-94.762 42.426-94.762 94.762 0 52.335 42.426 94.762 94.762 94.762z" fill-rule="evenodd"/></g><g><path clip-rule="evenodd" d="m132.667 94.762c0 2.616-2.122 4.738-4.738 4.738h-66.334c-2.617 0-4.738-2.122-4.738-4.738s2.121-4.738 4.738-4.738h66.333c2.617 0 4.739 2.122 4.739 4.738z" fill-rule="evenodd"/></g><g><path clip-rule="evenodd" d="m94.762 132.667c-2.616 0-4.738-2.122-4.738-4.738v-66.334c0-2.617 2.122-4.738 4.738-4.738s4.738 2.121 4.738 4.738v66.333c0 2.617-2.122 4.739-4.738 4.739z" fill-rule="evenodd"/></g></g></svg> {{ trans('labutton.invite_register') }}
                                            </a>
                                        @endif  --}}
                                        <a class="dropdown-item p-1" href="javascript:void(0)" id="send-mail-user-registed" style="cursor: pointer;">
                                            <svg class="w_15" xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512"><path d="m478.5 83.5h-385c-18.472 0-33.5 15.028-33.5 33.5v71.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5h90c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-22.5v-71.5c0-2.342.455-4.576 1.253-6.64l145.64 145.64-145.64 145.64c-.798-2.064-1.253-4.298-1.253-6.64v-49c0-4.143-3.358-7.5-7.5-7.5s-7.5 3.357-7.5 7.5v49c0 18.472 15.028 33.5 33.5 33.5h385c18.472 0 33.5-15.028 33.5-33.5v-278c0-18.472-15.028-33.5-33.5-33.5zm-128.393 172.5 145.64-145.64c.798 2.064 1.253 4.298 1.253 6.64v278c0 2.342-.455 4.576-1.253 6.64zm128.393-157.5c2.342 0 4.576.455 6.64 1.253l-167.32 167.32c-17.545 17.547-46.094 17.547-63.64 0l-167.32-167.32c2.064-.798 4.298-1.253 6.64-1.253zm-385 315c-2.342 0-4.576-.455-6.64-1.253l145.64-145.64 11.074 11.074c11.697 11.696 27.062 17.545 42.427 17.545s30.729-5.849 42.426-17.545l11.074-11.074 145.64 145.64c-2.064.798-4.298 1.253-6.64 1.253z"/><path d="m67.5 218.5c-4.142 0-7.5 3.357-7.5 7.5v22.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5h120c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-52.5v-22.5c0-4.143-3.358-7.5-7.5-7.5z"/><path d="m97.5 323.5c4.142 0 7.5-3.357 7.5-7.5s-3.358-7.5-7.5-7.5h-22.5v-22.5c0-4.143-3.358-7.5-7.5-7.5s-7.5 3.357-7.5 7.5v22.5h-52.5c-4.142 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5z"/></svg> {{ trans('labutton.send_mail_registed') }}
                                        </a>
                                    @endcanany
                                </div>
                            </div>
                        </div>
                        @canany(['offline-course-register-create'])
                            <div class="btn-group">
                                <a href="{{ route('module.offline.register.class.create', ['id' => $course->id,'class_id'=>$class->id]) }}" class="btn"><i class="fa fa-plus-circle"></i> {{ trans('labutton.add_new') }}</a>
                                <button class="btn" id="delete-item"><i class="fa fa-trash"></i> {{ trans('labutton.delete') }}</button>
                            </div>
                        @endcanany
                    </div>
                </div>
            </div>
            @endif
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 50, 100, 200, 500]" id="list-user-registed">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-width="25%" data-field="full_name" data-formatter="full_name_formatter">{{ trans('latraining.employee_name') }}</th>
                    <th data-field="title_name">{{ trans('latraining.title') }}</th>
                    <th data-field="unit_name">{{ trans('latraining.work_unit') }}</th>
                    <th data-field="parent_unit_name">{{ trans('latraining.unit_manager') }}</th>
                    <th data-field="register_form" data-width="10%" data-align="center" data-formatter="register_formatter">{{trans('latraining.register_method')}}</th>
                    <th data-field="approved_step" data-align="center" data-formatter="approved_formatter" data-width="5%">{{ trans('latraining.approve') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{ trans('latraining.status') }}</th>
                    <th data-formatter="info_formatter" data-align="center" data-width="5%">{{ trans('latraining.info') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.offline.register.import_register', ['id' => $course_id, 'class_id' => $class->id]) }}" method="post" class="form-ajax">
                <input type="hidden" name="unit" value="{{ $course->unit_id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.import_user') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                        <button type="submit" class="btn">{{ trans('labutton.import') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($course->quiz_id)
    <div class="modal fade" id="modal-add-to-quiz" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('latraining.add_student') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="part_id">{{trans('latraining.part')}}</label>
                        <select name="part_id" id="part_id" class="form-control select2" data-placeholder="{{trans('latraining.choose_part')}}">
                            <option value=""></option>
                            @php
                                $quiz_parts = isset($course->quiz_id) ? $quiz_part($course->quiz_id) : [];
                            @endphp
                            @foreach($quiz_parts as $part)
                                <option value="{{ $part->id }}">{{ $part->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" id="save"><i class="fa fa-plus"></i>
                    {{ trans('labutton.add_new') }}</button>
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                </div>

            </div>
        </div>
    </div>
    @endif

    <div class="modal fade" id="modal-invite-user-register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans('latraining.invite_register') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('latraining.person_role') }}</label>
                        <select name="user_id" id="user_id" class="form-control select2" data-placeholder="-- {{ trans('latraining.employee_name') }} --" required>
                            <option value=""></option>
                            @foreach ($user_has_role_register as $user_has_role)
                                <option value="{{ $user_has_role->user_id }}" data-role="{{ $user_has_role->role_id }}">{{ \App\Models\Profile::fullname($user_has_role->user_id) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('latraining.num_register') }}</label>
                        <input type="text" name="num_register" class="form-control is-number" value="" placeholder="{{ trans('latraining.num_register') }}" required>
                    </div>

                    <div class="form-group">
                        <div class="text-right">
                            <button id="delete-invite-user-role" class="btn"><i class="fa fa-trash"></i> {{trans('labutton.delete')}}</button>
                        </div>
                        <p></p>
                        <table class="tDefault table table-hover bootstrap-table" id="invite-user-role">
                            <thead>
                            <tr>
                                <th data-field="state" data-checkbox="true"></th>
                                <th data-field="index" data-formatter="index_formatter" data-width="3%" data-align="center">#</th>
                                <th data-field="user_code">{{ trans('latraining.employee_code') }}</th>
                                <th data-field="user_name">{{ trans('latraining.employee_name') }}</th>
                                <th data-field="num_register" data-align="center">{{ trans('latraining.num_register') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">{{ trans('labutton.close') }}</button>
                    <button type="submit" class="btn" id="invite-user">{{ trans('labutton.save') }}</button>
                </div>
            </div>

            <script type="text/javascript">
                var table_invite_user = new LoadBootstrapTable({
                    locale: '{{ \App::getLocale() }}',
                    url: '{{ route('module.offline.register.getdata.invite_user', ['id' => $course->id]) }}',
                    remove_url: '{{ route('module.offline.register.remove.invite_user', ['id' => $course->id]) }}',
                    table: '#invite-user-role',
                    detete_button: '#delete-invite-user-role',
                });

                function index_formatter(value, row, index) {
                    return index + 1;
                }
            </script>
        </div>
    </div>

    <script type="text/javascript">
        function info_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="load-modal" data-url="'+row.info_url+'"> <i class="fa fa-info-circle"></i></a>';
        }

        function full_name_formatter(value, row, index){
            return row.full_name +' ('+ row.code +') <p>'+ row.email + '</p>';
        }

        function status_formatter(value, row, index) {
            if (value == 1) {
                return '<span class="text-success">{{ trans("latraining.approved") }}</span>';
            }
            else if (value == 0) {
                return '<span class="text-danger">{{ trans("latraining.deny") }}</span>';
            }
            else {
                return '<span class="text-warning">{{ trans("latraining.not_approved") }}</span>';
            }

        }
        function register_formatter(value, row, index) {
            if (row.register_form == 1) {
                return '<span title="Học viên đăng ký">HVĐK</span>';
            } else if (row.register_form == 2) {
                return '<span title="Tự động ghi danh">TĐGD <i class="fa fa-info-circle" title="Thời gian ghi danh: '+ (row.time_register) +'"></i></span>';
            } else{
                return '<span title="Quản trị ghi danh">QTGD</span>';
            }
        }
        function approved_formatter(value, row, index) {
            return value? `<a href="javascript:void(0)" data-id="${row.id}" data-model="el_offline_register" class="text-success font-weight-bold load-modal-approved-step">${value}</a>`:'-';
        }
        function unit_status_formatter(value, row, index) {
            return row.status_level_1 == 1 ? '<span class="text-primary">{{ trans("latraining.approved") }}</span>' : row.status_level_1 == 0 ? '<span ' +
                'class="text-danger">{{ trans("latraining.deny") }}</span>' : '<span class="text-warning">{{ trans("latraining.not_approved") }}</span>';
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.register.getdata', ['id' => $course_id, 'class_id' => $class->id]) }}',
            remove_url: '{{ route('module.offline.register.remove', ['id' => $course_id]) }}',
            table: '#list-user-registed',
            form_search: '#form-search-user',
        });

        $("#add-to-quiz").on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 học viên!', 'error');
                return false;
            }
            $('#modal-add-to-quiz').modal();

            $('#save').on('click', function () {
                var part_id = $('#part_id option:selected').val();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('module.offline.register.add_to_quiz', ['id' => $course->id]) }}',
                    dataType: 'json',
                    data: {
                        'ids': ids,
                        'part_id': part_id,
                    }
                }).done(function(data) {
                    show_message(data.message, data.status);
                    $('#modal-add-to-quiz').hide();
                    window.location = '';
                    return false;
                }).fail(function(data) {
                    return false;
                });
            });
        });

        $("#invite-user-register").on('click', function () {
            $('#modal-invite-user-register').modal();

            $('#invite-user').on('click', function () {
                var user_id = $('#user_id option:selected').val();
                var role_id = $('#user_id option:selected').data('role');
                var num_register = $("input[name=num_register]").val();

                if(!user_id){
                    show_message('Vui lòng chọn nhân viên!', 'error');
                    return false;
                }

                if(!num_register){
                    show_message('Vui lòng nhập SL ghi danh!', 'error');
                    return false;
                }

                $.ajax({
                    type: 'POST',
                    url: '{{ route('module.offline.register.invite_user', ['id' => $course->id]) }}',
                    dataType: 'json',
                    data: {
                        'user_id': user_id,
                        'role_id': role_id,
                        'num_register': num_register,
                    }
                }).done(function(data) {
                    $("#user_id").val('').trigger('change');
                    $("input[name=num_register]").val('').trigger('change');

                    table_invite_user.refresh();

                    return false;
                }).fail(function(data) {
                    return false;
                });
            });
        });

        $('#send-mail-user-registed').on('click', function () {
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 học viên!', 'error');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '{{ route('module.offline.register.send_mail_user_registed', ['id' => $course_id]) }}',
                dataType: 'json',
                data: {
                    'ids': ids,
                }
            }).done(function(data) {
                show_message(data.message, data.status);
                table.refresh();
                return false;
            }).fail(function(data) {
                return false;
            });
        })
    </script>
@endsection
