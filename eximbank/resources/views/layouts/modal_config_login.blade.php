@php
    $name_experience_navigate = \App\Models\SettingExperienceNavigateName::where('status', 1)->get();
    $profile_name = profile();
@endphp
<div class="modal" id="modal_show_config" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mục tiêu của Bạn khi đến với hệ thống là gì?</h5>
                <button onclick="saveCountNavigate()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @foreach ($name_experience_navigate as $key => $item)
                        @php
                            $language = \App::getlocale();
                            $name =  (array) json_decode($item->name);
                        @endphp
                        <div class="col-md-4 col-6 text-center wrapped_navigate d_flex_align">
                            @if ($item->type == 1)
                                <button class="w-100 mb-2 btn" onclick="setConfig({{ $key + 1 }})">{{ $name[$language] }}</button>
                            @else
                                <img class="cursor_pointer" src="{{ image_file($item->image) }}" onclick="setConfig({{ $key + 1 }})" alt="" width="150px">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal_set_config" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <input type="hidden" id="link_config" value="">
                <div class="row">
                    <div class="col-12">
                        <h4 class="p-2 title_set_config">
                            Hệ thống ghi nhận mục tiêu của <strong>{{ $profile_name->firstname }}</strong>. Hệ thống đang khởi tạo nội dung liên quan để đáp ứng mục tiêu của <strong>{{ $profile_name->firstname }}</strong>. <br> 
                        </h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="redirectConfig()" class="btn">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
