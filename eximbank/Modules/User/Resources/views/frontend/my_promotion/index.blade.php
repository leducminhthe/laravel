<div class="">
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="setting_tabs">
                    <ul class="nav nav-pills mb-4 " id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active nav_promotion" id="pills-account-tab" data-toggle="pill" href="#pills-account" role="tab" aria-selected="false">@lang('laprofile.list')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav_promotion" id="pills-notification-tab" data-toggle="pill" href="#pills-notification" role="tab" aria-selected="false">@lang('laprofile.history')</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade active show" id="pills-account" role="tabpanel" aria-labelledby="pills-account-tab">
                        <div class="account_setting">
                            @include('user::frontend.my_promotion.orders')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-notification" role="tabpanel" aria-labelledby="pills-notification-tab">
                        <div class="account_setting">
                            @include('user::frontend.my_promotion.history')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

