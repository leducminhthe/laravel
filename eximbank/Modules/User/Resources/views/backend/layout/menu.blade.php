<div class="row pb-2" id="menu_user_backend">
    <div class="col-md-12 text-center p-1">
        {{--<a href="{{route('module.user.info')}}" class="btn ">--}}
            {{--<div><i class="fa fa-user"></i></div>--}}
            {{--<div>Thông tin tài khoản</div>--}}
        {{--</a>--}}
        @if(isset($model))
            <a href="javascript:void(0)" id="change-avatar">
                <img class="avatar_user_edit" src="{{ \App\Models\Profile::avatar($model->id) }}" alt="">
            </a>
        @endif
        @if(userCan('user-view-roadmap') || \App\Models\Permission::isUnitManager())
            <a href="{{route('module.backend.user.roadmap',['user_id'=>$user_id])}}" class="btn btn_tab_user mb-2">
                <svg class="w_25px" xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 74 74" width="512">
                    <g id="Icons"><path d="m19.409 72a1 1 0 0 1 -.96-1.282l14.063-47.818a1 1 0 0 1 1.919.564l-14.063 47.818a1 1 0 0 1 -.959.718z"/><path d="m54.591 72a1 1 0 0 1 -.959-.718l-14.063-47.817a1 1 0 0 1 1.919-.564l14.063 47.817a1 1 0 0 1 -.96 1.282z"/><path d="m37 25.435a6.585 6.585 0 1 1 6.585-6.585 6.592 6.592 0 0 1 -6.585 6.585zm0-11.17a4.585 4.585 0 1 0 4.585 4.585 4.59 4.59 0 0 0 -4.585-4.585z"/><path d="m50.213 57.13a.989.989 0 0 1 -.488-.13l-13.148-7.374a.879.879 0 0 1 -.087-.043l-9.458-5.309a1 1 0 1 1 .979-1.744l9.382 5.27c.029.013.058.027.086.043l13.221 7.415a1 1 0 0 1 -.49 1.872z"/><path d="m27.708 43.792a1 1 0 0 1 -.621-1.784l15.077-11.931a1 1 0 0 1 1.241 1.569l-15.077 11.93a.99.99 0 0 1 -.62.216z"/><path d="m20.981 66.651a1 1 0 0 1 -.294-1.956l29.366-9.075a1 1 0 0 1 .591 1.911l-29.367 9.075a1.008 1.008 0 0 1 -.296.045z"/><path d="m53.019 66.661a1.008 1.008 0 0 1 -.3-.045l-29.363-9.075a1 1 0 0 1 .59-1.911l29.367 9.075a1 1 0 0 1 -.294 1.956z"/><path d="m23.787 57.109a1 1 0 0 1 -.49-1.872l13.193-7.4c.03-.017.059-.031.089-.045l9.4-5.284a1 1 0 1 1 .98 1.743l-9.478 5.33c-.03.016-.06.031-.091.045l-13.115 7.355a.989.989 0 0 1 -.488.128z"/><path d="m46.3 43.823a1 1 0 0 1 -.62-.216l-15.1-11.951a1 1 0 1 1 1.24-1.569l15.1 11.953a1 1 0 0 1 -.622 1.783z"/><path d="m62.49 11.55a1 1 0 0 1 -.935-.647 20.263 20.263 0 0 0 -4.662-7.2 1 1 0 0 1 1.414-1.414 22.244 22.244 0 0 1 5.119 7.9 1 1 0 0 1 -.936 1.354z"/><path d="m57.6 34.45a1 1 0 0 1 -.709-1.705 20.456 20.456 0 0 0 4.034-5.72 1 1 0 0 1 1.81.85 22.432 22.432 0 0 1 -4.426 6.28.994.994 0 0 1 -.709.295z"/><path d="m63.792 21.45c-.036 0-.07 0-.105 0a1 1 0 0 1 -.892-1.1 20.545 20.545 0 0 0 .1-2.766 1 1 0 0 1 .969-1.031.985.985 0 0 1 1.03.969 22.553 22.553 0 0 1 -.114 3.035 1 1 0 0 1 -.988.893z"/><path d="m52.671 29.527a1 1 0 0 1 -.707-1.707 13.584 13.584 0 0 0 0-19.187 1 1 0 0 1 1.414-1.414 15.586 15.586 0 0 1 0 22.015 1 1 0 0 1 -.707.293z"/><path d="m47.745 24.6a1 1 0 0 1 -.707-1.707 6.608 6.608 0 0 0 0-9.334 1 1 0 0 1 1.414-1.414 8.61 8.61 0 0 1 0 12.162 1 1 0 0 1 -.707.293z"/><path d="m16.4 34.453a1 1 0 0 1 -.707-.293 22.558 22.558 0 0 1 0-31.867 1 1 0 0 1 1.417 1.414 20.557 20.557 0 0 0 0 29.039 1 1 0 0 1 -.707 1.707z"/><path d="m21.329 29.527a1 1 0 0 1 -.707-.293 15.586 15.586 0 0 1 0-22.015 1 1 0 0 1 1.414 1.414 13.584 13.584 0 0 0 0 19.187 1 1 0 0 1 -.707 1.707z"/><path d="m26.255 24.6a1 1 0 0 1 -.707-.293 8.61 8.61 0 0 1 0-12.162 1 1 0 0 1 1.414 1.414 6.608 6.608 0 0 0 0 9.334 1 1 0 0 1 -.707 1.707z"/></g>
                </svg>
                <div>{{ trans('laprofile.roadmap') }}</div>
            </a>
        @endif
        @if(userCan('user-view-training-process') || \App\Models\Permission::isUnitManager())
            <a href="{{route('module.backend.user.trainingprocess',['user_id'=>$user_id])}}" class="btn btn_tab_user mb-2">
                <svg class="w_25px" xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 512 512" width="512">
                    <g id="_OUTLINE" data-name="/ OUTLINE"><path d="m392 288a103.74358 103.74358 0 0 0 -34.666 5.91992l5.332 15.08545a88.02394 88.02394 0 0 1 85.61523 15.3999l-56.28123 56.28125-34.34326-34.34326-11.31348 11.31348 37.65674 37.65674v84.319a88.06939 88.06939 0 0 1 -74.99463-116.96648l-15.08545-5.332a104.01623 104.01623 0 1 0 98.08008-69.334zm8 191.63251v-75.20862l68.45264 31.11481a88.13313 88.13313 0 0 1 -68.45264 44.09381zm75.09094-58.65143-69.27124-31.48688 53.775-53.77539a87.877 87.877 0 0 1 15.49621 85.26227z"/><path d="m48.65576 239.36865c-.43506 5.49561-.65576 11.09135-.65576 16.63135a208.871 208.871 0 0 0 6.02979 49.91357l15.53515-3.82714a192.87356 192.87356 0 0 1 -5.56494-46.08643c0-5.12061.20361-10.2915.606-15.36865z"/><path d="m240.63135 447.394-1.2627 15.9502c5.49561.4351 11.09082.6558 16.63135.6558a210.19334 210.19334 0 0 0 33.27051-2.64795l-2.541-15.79687a195.37869 195.37869 0 0 1 -46.09816 1.83882z"/><path d="m461.35205 289.27051a210.5224 210.5224 0 0 0 1.166-58.22022l-15.88672 1.89942a194.53387 194.53387 0 0 1 -1.07617 53.77978z"/><path d="m256 64a191.71394 191.71394 0 0 1 61.44141 10.03906l5.11718-15.16015a207.69662 207.69662 0 0 0 -66.55859-10.87891 210.13551 210.13551 0 0 0 -33.27051 2.648l2.541 15.79687a194.14247 194.14247 0 0 1 30.72951-2.44487z"/><path d="m284.29346 345.70264 15.07275-6.2461.02393-.00976 26.65576 16.33447 29.73486-29.73486-16.33447-26.65625 6.25635-15.09668 30.29736-7.26465v-42.05762l-30.29736-7.26465-6.2461-15.07275-.00976-.02393 16.33447-26.65576-29.73486-29.73486-26.65625 16.33447-15.09668-6.25635-7.26465-30.29736h-42.05762l-7.26465 30.29736-15.07275 6.2461-.02393.00976-26.65576-16.33447-29.73486 29.73486 16.33447 26.65625-6.25635 15.09668-30.29736 7.26465v42.05762l30.29736 7.26465 6.2461 15.07275.00976.02393-16.33447 26.65576 29.73486 29.73486 26.65625-16.33447 15.09668 6.25635 7.26465 30.29736h42.05762zm-19.88184 14.29736h-16.82324l-6.22363-25.95752-30.16211-12.49848-22.8545 14.00439-11.897-11.897 14.0044-22.854-12.499-30.16211-25.95654-6.22366v-16.82324l25.95752-6.22363 12.49853-30.16211-14.00439-22.8545 11.897-11.897 22.854 14.0044 30.16211-12.499 6.22361-25.95654h16.82324l6.22363 25.95752 30.16211 12.49853 22.8545-14.00439 11.897 11.897-14.0044 22.854 12.499 30.16211 25.95654 6.22361v16.82324l-25.95752 6.22363-12.49848 30.16211 14.00439 22.8545-11.897 11.897-22.854-14.0044-30.16211 12.499z"/><path d="m320 256a64 64 0 1 0 -64 64 64.0727 64.0727 0 0 0 64-64zm-64 48a48 48 0 1 1 48-48 48.05436 48.05436 0 0 1 -48 48z"/><path d="m248 252.687-21.657 21.656 11.314 11.314 26.343-26.344v-35.313h-16z"/><path d="m472 16h-112a24.0275 24.0275 0 0 0 -24 24v104h16v-104a8.00917 8.00917 0 0 1 8-8h112a8.00917 8.00917 0 0 1 8 8v112h-32a24.02687 24.02687 0 0 0 -24 24v32h-40v16h41.37256a23.843 23.843 0 0 0 16.9707-7.02979l46.627-46.627a23.843 23.843 0 0 0 7.02974-16.97065v-113.37256a24.0275 24.0275 0 0 0 -24-24zm-32 180.68652v-20.68652a8.00917 8.00917 0 0 1 8-8h20.68652z"/><path d="m368 48h16v16h-16z"/><path d="m400 48h64v16h-64z"/><path d="m368 80h16v16h-16z"/><path d="m400 80h64v16h-64z"/><path d="m368 112h16v16h-16z"/><path d="m400 112h64v16h-64z"/><path d="m48 168h96v16h-96z"/><path d="m48 136h96v16h-96z"/><path d="m141.74658 395.708-27.50342-22.91944a16.00017 16.00017 0 0 0 -26.24316 12.29201v45.83886a15.85566 15.85566 0 0 0 9.21338 14.48975 16.06924 16.06924 0 0 0 6.82617 1.53516 15.89776 15.89776 0 0 0 10.20361-3.73291l27.50391-22.91993a16.00125 16.00125 0 0 0 -.00049-24.58349zm-37.74658 35.20655.00391-45.83105 27.49951 22.9165z"/><path d="m208 472a8.00917 8.00917 0 0 1 -8 8h-160a8.00917 8.00917 0 0 1 -8-8v-128a8.00917 8.00917 0 0 1 8-8h104v-16h-104a24.0275 24.0275 0 0 0 -24 24v128a24.0275 24.0275 0 0 0 24 24h160a24.0275 24.0275 0 0 0 24-24v-96h-16z"/><path d="m368 144h16v16h-16z"/><path d="m400 144h16v16h-16z"/><path d="m40 224h96v-16h-96a8.00917 8.00917 0 0 1 -8-8v-128a8.00917 8.00917 0 0 1 8-8h8v52.94434l24-12 24 12v-52.94434h56a8.00917 8.00917 0 0 1 8 8v72h16v-72a24.0275 24.0275 0 0 0 -24-24h-88v-8a8.00917 8.00917 0 0 1 8-8h112a8.00917 8.00917 0 0 1 8 8v104h16v-104a24.02687 24.02687 0 0 0 -24-24h-112a24.02687 24.02687 0 0 0 -24 24v8h-8a24.0275 24.0275 0 0 0 -24 24v128a24.0275 24.0275 0 0 0 24 24zm24-160h16v27.05566l-8-4-8 4z"/></g>
                </svg>
                <div>{{ trans('laprofile.training_process') }}</div>
            </a>
        @endif
        @if(userCan('user-view-quiz-result') || \App\Models\Permission::isUnitManager())
            <a href="{{route('module.backend.user.quizresult',['user_id'=>$user_id])}}" class="btn btn_tab_user mb-2">
                <svg class="w_25px" xmlns="http://www.w3.org/2000/svg" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512">
                    <g><g><path d="m430.17 0h-348.34c-13.348 0-24.207 10.859-24.207 24.207v463.586c0 13.348 10.859 24.207 24.207 24.207h283.629c.04 0 .079-.005.119-.006 1.403-.022 2.841-.46 4.02-1.24.407-.27.801-.59 1.148-.935 0 0 81.382-80.909 81.42-80.947 1.433-1.424 2.211-3.305 2.211-5.317 0-.261 0-399.349 0-399.349 0-13.347-10.859-24.206-24.207-24.206zm-57.213 486.476v-50.675c0-1.282.499-2.486 1.406-3.392.906-.905 2.109-1.403 3.39-1.403h.004l50.96.035zm66.424-70.423-61.615-.043c-.006 0-.01 0-.015 0-5.283 0-10.252 2.057-13.989 5.792-3.742 3.739-5.802 8.71-5.802 13.999v61.204h-276.13c-5.079 0-9.211-4.133-9.211-9.211v-463.587c0-5.079 4.132-9.211 9.211-9.211h348.34c5.079 0 9.211 4.132 9.211 9.211z"/><path d="m304.83 158.001c3.869 1.472 8.202-.468 9.676-4.338l5.152-13.527h34.752l5.096 13.505c1.133 3.002 3.986 4.853 7.015 4.852.88 0 1.775-.156 2.647-.485 3.874-1.462 5.829-5.788 4.367-9.662l-27.815-73.705c-.025-.068-.053-.136-.08-.204-1.423-3.461-4.759-5.696-8.501-5.696-.003 0-.007 0-.01 0-3.746.004-7.082 2.247-8.498 5.714-.023.056-.045.111-.066.167l-28.071 73.703c-1.475 3.87.468 8.202 4.336 9.676zm32.284-63.695 11.637 30.836h-23.381z"/><path d="m300.387 185.942c10.598 4.189 21.994 6.204 33.276 6.203 22.27 0 44.093-7.851 58.449-22.318 27.945-28.164 22.592-76.6.059-104.168-18.51-22.648-51.729-33.027-96.06-30.014-4.13.281-7.252 3.858-6.971 7.989.28 4.132 3.868 7.259 7.989 6.972 27.389-1.863 64.097.887 83.433 24.543 18.024 22.052 22.83 62.021.906 84.115-17.309 17.444-49.796 22.918-75.567 12.731-11.493-4.543-30.887-16.096-31.643-42.429-.647-22.516 12.208-41.687 33.549-50.032 3.856-1.508 5.76-5.857 4.253-9.713-1.508-3.856-5.854-5.761-9.714-4.252-27.405 10.716-43.911 35.403-43.077 64.428.73 25.514 15.718 45.905 41.118 55.945z"/><path d="m107.648 231.566h296.704c4.141 0 7.498-3.357 7.498-7.498s-3.356-7.498-7.498-7.498h-296.704c-4.141 0-7.498 3.357-7.498 7.498s3.356 7.498 7.498 7.498z"/><path d="m404.352 253.378h-92.985c-4.141 0-7.498 3.357-7.498 7.498s3.356 7.498 7.498 7.498h92.985c4.141 0 7.498-3.357 7.498-7.498s-3.357-7.498-7.498-7.498z"/><path d="m107.648 268.373h168.938c4.141 0 7.498-3.357 7.498-7.498s-3.356-7.498-7.498-7.498h-168.938c-4.141 0-7.498 3.357-7.498 7.498s3.356 7.498 7.498 7.498z"/><path d="m107.648 305.18h296.704c4.141 0 7.498-3.357 7.498-7.498s-3.356-7.498-7.498-7.498h-296.704c-4.141 0-7.498 3.357-7.498 7.498s3.356 7.498 7.498 7.498z"/><path d="m107.648 341.987h296.704c4.141 0 7.498-3.357 7.498-7.498s-3.356-7.498-7.498-7.498h-296.704c-4.141 0-7.498 3.357-7.498 7.498s3.356 7.498 7.498 7.498z"/><path d="m404.593 364.039h-215.977c-4.141 0-7.498 3.357-7.498 7.498s3.356 7.498 7.498 7.498h215.978c4.141 0 7.498-3.357 7.498-7.498s-3.358-7.498-7.499-7.498z"/><path d="m107.648 378.793h46.544c4.141 0 7.498-3.357 7.498-7.498s-3.356-7.498-7.498-7.498h-46.544c-4.141 0-7.498 3.357-7.498 7.498 0 4.142 3.356 7.498 7.498 7.498z"/><path d="m317.267 400.846h-61.026c-4.141 0-7.498 3.357-7.498 7.498s3.356 7.498 7.498 7.498h61.026c4.141 0 7.498-3.357 7.498-7.498s-3.357-7.498-7.498-7.498z"/><path d="m221.422 400.846h-113.533c-4.141 0-7.498 3.357-7.498 7.498s3.356 7.498 7.498 7.498h113.533c4.141 0 7.498-3.357 7.498-7.498-.001-4.141-3.357-7.498-7.498-7.498z"/></g></g>
                </svg>
                <div>{{ trans('laprofile.quiz_result') }}</div>
            </a>
        @endif
        @if(userCan('user-view-training-program-learned') || \App\Models\Permission::isUnitManager())
            <a href="{{route('module.backend.training_program_learned',['user_id'=>$user_id])}}" class="btn btn_tab_user mb-2">
                <svg class="w_25px" xmlns="http://www.w3.org/2000/svg" id="Layer_1" enable-background="new 0 0 511.773 511.773" height="512" viewBox="0 0 511.773 511.773" width="512">
                    <g><path d="m56.619 39.591c-9.652 0-9.668 15 0 15 9.652 0 9.668-15 0-15z"/><path d="m90.817 39.591c-9.652 0-9.668 15 0 15 9.652 0 9.668-15 0-15z"/><path d="m125.016 39.591c-9.652 0-9.668 15 0 15 9.651 0 9.667-15 0-15z"/><path d="m55.414 153.114h226.732c9.697 0 9.697-15 0-15h-226.732c-9.697 0-9.697 15 0 15z"/><path d="m55.414 198.965h226.732c9.697 0 9.697-15 0-15h-226.732c-9.697 0-9.697 15 0 15z"/><path d="m146.158 237.315c0 4.143 3.358 7.5 7.5 7.5h249.971c9.697 0 9.697-15 0-15h-249.971c-4.142 0-7.5 3.358-7.5 7.5z"/><path d="m55.414 244.815h65.093c9.697 0 9.697-15 0-15h-65.093c-9.697 0-9.697 15 0 15z"/><path d="m55.414 290.666h348.215c9.697 0 9.697-15 0-15h-348.215c-9.697 0-9.697 15 0 15z"/><path d="m55.414 336.517h160.416c9.697 0 9.697-15 0-15h-160.416c-9.697 0-9.697 15 0 15z"/><path d="m502.81 369.529-42.792-16.052v-316.776c-.4-20.309-16.153-36.329-36.516-36.701h-386.968c-20.362.405-36.151 16.39-36.517 36.701v87.94c0 9.697 15 9.697 15 0v-30.458h307.615v96.817c0 5.402 6.406 9.677 11.605 6.276l32.643-21.352 32.643 21.352c4.918 3.218 11.605-.427 11.605-6.276v-96.817h33.888v253.668l-79.752-29.916c-6.272-2.352-13.182-2.352-19.457 0l-137.542 51.594c-5.308 1.99-8.827 6.783-9.174 12.362h-162.741c-11.763 0-21.333-9.735-21.333-21.701v-190.576c0-9.697-15-9.697-15 0v190.576c0 20.237 16.299 36.701 36.333 36.701h174.11l56.732 21.28v48.796c0 2.51 1.255 4.853 3.344 6.243 28.78 19.159 56.061 28.473 83.4 28.473 23.847 0 48.468-7.1 74.948-21.655v24.472c0 9.697 15 9.697 15 0v-86.524l15.783-5.959c9.072-3.425 3.775-17.457-5.299-14.033l-17.904 6.76-78.284-29.465c-9.076-3.41-14.361 10.623-5.284 14.039l62.311 23.452-55.209 20.847c-2.876 1.079-6.044 1.08-8.92-.001l-135.478-50.817 135.476-50.818c2.877-1.077 6.046-1.077 8.921 0l135.836 50.953c-6.823 4.863-1.17 16.191 6.976 13.135 11.842-4.443 12.014-22.034.001-26.54zm-487.793-290.346v-42.482c0-11.756 9.585-21.701 21.517-21.701h286.099v64.183zm381.112 97.95-25.143-16.446c-1.247-.815-2.676-1.224-4.105-1.224s-2.858.408-4.105 1.224l-25.143 16.445v-162.132h58.497v162.133zm15-97.95v-64.183h12.373c11.97 0 21.516 9.896 21.516 21.701v42.481h-33.889zm-55.595 370.243c3.3 0 6.602-.59 9.748-1.771l63.602-24.015v39.105c-27.036 16.103-51.587 23.938-74.948 23.938-23.252 0-46.757-7.784-71.744-23.775v-39.11l63.617 23.862c3.135 1.177 6.429 1.766 9.725 1.766z"/></g>
                </svg>
                <div>{{ trans('laprofile.training_program_learned') }}</div>
            </a>
        @endif
        @if(userCan('user-view-working-process') || \App\Models\Permission::isUnitManager())
            <a href="{{route('module.backend.working_process',['user_id'=>$user_id])}}" class="btn btn_tab_user mb-2">
                <svg class="w_25px" xmlns="http://www.w3.org/2000/svg" id="Layer_5" enable-background="new 0 0 64 64" height="512" viewBox="0 0 64 64" width="512">
                    <g><path d="m13.035 7.035c-3.309 0-6 2.691-6 6s2.691 6 6 6 6-2.691 6-6-2.691-6-6-6zm0 10c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4z"/><path d="m57.707 48.707-1.414-1.414-7.293 7.293-1.293-1.293-1.414 1.414 2.707 2.707z"/><path d="m35 12h2v2h-2z"/><path d="m47 12h2v2h-2z"/><path d="m43 12h2v2h-2z"/><path d="m39 12h2v2h-2z"/><path d="m24 37h16c1.654 0 3-1.346 3-3v-1h13c3.859 0 7-3.14 7-7v-7c0-3.86-3.141-7-7-7h-3v-1c0-1.654-1.346-3-3-3h-16c-1.654 0-3 1.346-3 3v1h-5.965v-1.728l-2.625-.717c-.086-.23-.182-.459-.286-.685l1.351-2.366-3.908-3.908-2.365 1.35c-.227-.104-.455-.2-.685-.286l-.718-2.625h-5.527l-.718 2.625c-.23.086-.458.181-.684.286l-2.366-1.35-3.908 3.908 1.35 2.366c-.104.226-.2.455-.286.685l-2.625.717v5.526l2.625.718c.086.23.182.459.286.685l-1.351 2.366 3.908 3.907 2.366-1.35c.226.104.454.2.684.286l.718 2.625h5.527l.718-2.625c.229-.086.458-.182.685-.286l2.365 1.349 3.908-3.907-1.351-2.366c.104-.226.2-.455.286-.685l2.625-.718v-1.797h5.966v1c0 1.654 1.346 3 3 3h16c1.654 0 3-1.346 3-3v-1h3c2.757 0 5 2.243 5 5v7c0 2.757-2.243 5-5 5h-13v-1c0-1.654-1.346-3-3-3h-16c-1.654 0-3 1.346-3 3v1h-13c-3.859 0-7 3.141-7 7v8c0 3.859 3.141 7 7 7h3v1c0 1.654 1.346 3 3 3h16c1.654 0 3-1.346 3-3v-1h8.051c.507 5.598 5.221 10 10.949 10 6.065 0 11-4.935 11-11s-4.935-11-11-11c-5.728 0-10.442 4.402-10.949 10h-8.051v-1c0-1.654-1.346-3-3-3h-16c-1.654 0-3 1.346-3 3v1h-3c-2.757 0-5-2.243-5-5v-8c0-2.757 2.243-5 5-5h13v1c0 1.654 1.346 3 3 3zm-.965-22.728-2.193.6-.161.525c-.144.463-.336.924-.572 1.37l-.258.486 1.13 1.979-1.748 1.749-1.98-1.129-.485.257c-.445.236-.906.428-1.37.571l-.524.162-.601 2.193h-2.473l-.601-2.193-.524-.162c-.462-.143-.923-.334-1.37-.571l-.486-.257-1.979 1.129-1.75-1.749 1.13-1.979-.258-.486c-.236-.446-.429-.907-.572-1.37l-.161-.525-2.193-.6v-2.474l2.193-.599.162-.525c.143-.463.335-.924.571-1.369l.258-.487-1.13-1.979 1.748-1.749 1.979 1.129.486-.257c.449-.237.91-.43 1.37-.572l.524-.162.601-2.193h2.473l.601 2.193.524.162c.462.143.923.335 1.37.572l.485.257 1.98-1.129 1.748 1.749-1.13 1.979.258.486c.236.445.429.906.571 1.369l.162.525 2.193.599v2.475zm27.965.728c0 .551-.448 1-1 1h-16c-.552 0-1-.449-1-1v-4c0-.551.448-1 1-1h16c.552 0 1 .449 1 1zm1 28c4.963 0 9 4.037 9 9s-4.037 9-9 9-9-4.037-9-9 4.037-9 9-9zm-39 7c0-.552.448-1 1-1h16c.552 0 1 .448 1 1v4c0 .552-.448 1-1 1h-16c-.552 0-1-.448-1-1zm10-20c0-.551.448-1 1-1h16c.552 0 1 .449 1 1v4c0 .552-.448 1-1 1h-16c-.552 0-1-.448-1-1z"/><path d="m33 31h2v2h-2z"/><path d="m29 31h2v2h-2z"/><path d="m25 31h2v2h-2z"/><path d="m37 31h2v2h-2z"/><path d="m19 51h2v2h-2z"/><path d="m27 51h2v2h-2z"/><path d="m23 51h2v2h-2z"/><path d="m15 51h2v2h-2z"/></g>
                </svg>
                <div>{{ trans('laprofile.working_process') }}</div>
            </a>
        @endif
        @if(userCan('user-view-career-roadmap') || \App\Models\Permission::isUnitManager())
            <a href="{{route('module.career_roadmap.user',[$user_id])}}" class="btn btn_tab_user mb-2">
                <svg class="w_25px" xmlns="http://www.w3.org/2000/svg" id="Layer_1_1_" enable-background="new 0 0 64 64" height="512" viewBox="0 0 64 64" width="512">
                    <path d="m2 51v10c0 .552.447 1 1 1h14c.553 0 1-.448 1-1v-10c0-2.849-1.714-5.302-4.162-6.393 1.32-1.102 2.162-2.757 2.162-4.607 0-3.309-2.691-6-6-6s-6 2.691-6 6c0 1.85.842 3.505 2.162 4.607-2.448 1.091-4.162 3.544-4.162 6.393zm8-3.308.936 2.339-.936 2.807-.936-2.807zm0-3.692c-2.114 0-3.832-1.653-3.973-3.732 1.178-.817 2.536-1.268 3.973-1.268s2.795.451 3.973 1.268c-.141 2.079-1.859 3.732-3.973 3.732zm0-8c1.291 0 2.429.625 3.161 1.577-1.001-.377-2.064-.577-3.161-.577s-2.16.2-3.161.577c.732-.952 1.87-1.577 3.161-1.577zm-1.497 10.05-1.431 3.578c-.088.22-.095.463-.02.688l2 6c.136.409.517.684.948.684s.812-.275.948-.684l2-6c.075-.225.068-.468-.02-.688l-1.431-3.578c2.521.254 4.503 2.364 4.503 4.95v9h-12v-9c0-2.586 1.982-4.696 4.503-4.95z"/><path d="m57.231 41.36-4.299 5.159-2.226-2.226-1.414 1.414 3 3c.188.188.443.293.708.293.015 0 .03 0 .045-.001.281-.013.543-.143.724-.359l5-6z"/><path d="m24.933 30.519-2.226-2.226-1.414 1.414 3 3c.187.188.442.293.707.293.015 0 .03 0 .045-.001.281-.013.543-.143.724-.359l5-6-1.537-1.28z"/><path d="m57.948 10.266c-.119-.355-.427-.615-.797-.671l-3.618-.553-1.628-3.467c-.165-.351-.517-.575-.905-.575s-.74.224-.905.575l-1.628 3.467-3.618.553c-.37.057-.678.316-.797.671s-.029.748.232 1.016l2.647 2.713-.627 3.844c-.062.378.099.758.411.979.312.22.726.242 1.06.058l3.225-1.783 3.225 1.782c.15.083.317.125.483.125.202 0 .404-.062.576-.183.312-.22.473-.601.411-.979l-.627-3.844 2.647-2.713c.263-.267.352-.659.233-1.015zm-4.664 2.694c-.222.227-.322.546-.271.859l.377 2.309-1.906-1.053c-.15-.083-.316-.125-.483-.125s-.333.042-.483.125l-1.906 1.053.377-2.309c.051-.313-.05-.632-.271-.859l-1.641-1.682 2.222-.339c.329-.051.612-.262.754-.564l.947-2.022.949 2.022c.142.302.425.513.754.564l2.222.339z"/><path d="m55 58v-5.069c3.94-.495 7-3.859 7-7.931s-3.06-7.436-7-7.931v-7.069c0-.552-.447-1-1-1h-9l1.6-1.2-1.2-1.6-3.733 2.8h-7.667c0-4.072-3.06-7.436-7-7.931v-7.069h7l-1.6 1.2 1.199 1.6 4-3c.252-.188.4-.485.4-.8s-.148-.611-.4-.8l-4-3-1.199 1.6 1.6 1.2h-8c-.553 0-1 .448-1 1v8.069c-3.94.495-7 3.859-7 7.931 0 4.411 3.589 8 8 8 3.719 0 6.845-2.555 7.737-6h7.931l3.733 2.8 1.199-1.6-1.6-1.2h8v6.069c-3.94.495-7 3.859-7 7.931s3.06 7.436 7 7.931v4.069h-15.667l-3.733-2.8-1.2 1.6 1.6 1.2h-14v2h14l-1.6 1.2 1.199 1.6 3.733-2.8h16.668c.553 0 1-.448 1-1zm-29-23c-3.309 0-6-2.691-6-6s2.691-6 6-6 6 2.691 6 6-2.691 6-6 6zm22 10c0-3.309 2.691-6 6-6s6 2.691 6 6-2.691 6-6 6-6-2.691-6-6z"/><path d="m58.781 20.774-1.414-1.413c-.417.417-.876.794-1.363 1.12l1.113 1.662c.596-.399 1.155-.859 1.664-1.369z"/><path d="m61.166 17.208-1.848-.766c-.225.542-.504 1.065-.832 1.555l1.662 1.112c.401-.599.744-1.239 1.018-1.901z"/><path d="m62 12.958-2 .042c0 .591-.058 1.182-.171 1.755l1.961.389c.14-.702.21-1.423.21-2.144z"/><path d="m60.123 6.853c-.401-.595-.863-1.153-1.374-1.66l-1.408 1.42c.418.415.796.872 1.126 1.359z"/><path d="m61.149 8.75-1.846.772c.228.543.402 1.11.518 1.684l1.961-.396c-.141-.701-.354-1.394-.633-2.06z"/><path d="m46.76 2.847.771 1.846c.542-.227 1.108-.4 1.685-.516l-.395-1.961c-.704.141-1.397.354-2.061.631z"/><path d="m40 13.021 2-.021c0-.586.057-1.171.167-1.737l-1.963-.384c-.136.692-.204 1.406-.204 2.121z"/><path d="m44.9 22.156c.601.4 1.24.742 1.901 1.015l.764-1.849c-.541-.223-1.064-.503-1.556-.831z"/><path d="m43.201 5.242 1.418 1.41c.416-.418.874-.796 1.36-1.122l-1.115-1.661c-.594.4-1.154.861-1.663 1.373z"/><path d="m42.675 9.575c.224-.542.502-1.066.828-1.556l-1.664-1.109c-.399.6-.74 1.24-1.014 1.903z"/><path d="m49.253 21.831-.387 1.962c.698.138 1.416.207 2.134.207h.01l-.01-2c-.589 0-1.177-.057-1.747-.169z"/><path d="m40.843 17.229 1.846-.77c-.226-.541-.398-1.108-.514-1.685l-1.961.393c.141.706.352 1.399.629 2.062z"/><path d="m52.729 4.166.381-1.963c-.692-.135-1.402-.203-2.11-.203h-.031l.031 2c.58 0 1.162.056 1.729.166z"/><path d="m44.646 19.374c-.417-.416-.795-.874-1.124-1.362l-1.658 1.117c.4.596.861 1.154 1.37 1.661z"/><path d="m55.176 2.82-.76 1.851c.543.223 1.066.5 1.557.826l1.107-1.665c-.6-.399-1.24-.739-1.904-1.012z"/><path d="m52.767 21.827.389 1.962c.706-.14 1.4-.351 2.063-.627l-.768-1.847c-.542.226-1.108.398-1.684.512z"/>
                </svg>
                <div>{{ trans('laprofile.career_roadmap') }}</div>
            </a>
        @endif
        @if(userCan('user-create') || userCan('user-edit') || \App\Models\Permission::isUnitManager())
            <a href="{{route('module.backend.user_certificate',[$user_id])}}" class="btn btn_tab_user mb-2">
                <svg class="w_25px" xmlns="http://www.w3.org/2000/svg" id="Layer_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512">
                    <g><path d="m494.5 34.936h-477c-9.649 0-17.5 7.851-17.5 17.5v61.595c0 9.697 15 9.697 15 0v-61.595c0-1.378 1.121-2.5 2.5-2.5h477c1.379 0 2.5 1.122 2.5 2.5v365.739c0 1.378-1.121 2.5-2.5 2.5h-96.112v-15.606h75.434c4.143 0 7.5-3.358 7.5-7.5v-78.539c0-9.697-15-9.697-15 0v71.039h-67.934v-33.277c.244.021.49.042.721.063 6.226.547 13.97 1.228 19.335-4.147 5.359-5.369 4.681-13.121 4.136-19.35-.587-6.685.045-8.515 5.183-12.786 13.229-11 13.475-22.778 0-33.98-5.138-4.274-5.767-6.102-5.183-12.788 1.468-16.777-6.368-24.999-23.471-23.496-6.675.586-8.486-.048-12.742-5.187-10.983-13.26-22.769-13.505-33.956 0-4.256 5.138-6.066 5.775-12.741 5.187-16.789-1.475-24.97 6.405-23.472 23.496.584 6.687-.045 8.514-5.183 12.788-13.229 10.999-13.474 22.779 0 33.98 5.138 4.274 5.767 6.102 5.183 12.788-1.575 17.969 7.007 24.917 24.19 23.433v33.278h-294.709v-309.53h420.643v198.489c0 9.697 15 9.697 15 0v-205.989c0-4.142-3.357-7.5-7.5-7.5h-435.643c-4.143 0-7.5 3.358-7.5 7.5v324.528c0 4.142 3.357 7.5 7.5 7.5h302.209v15.606h-322.888c-1.379 0-2.5-1.122-2.5-2.5v-266.145c0-9.697-15-9.697-15 0v266.145c0 9.649 7.851 17.5 17.5 17.5h322.888v33.95c1.068 6.164 4.623 8.431 10.663 6.8l18.337-8.529 18.337 8.529c4.537 2.11 10.663-1.225 10.663-6.8v-33.95h96.112c9.649 0 17.5-7.851 17.5-17.5v-365.739c0-9.65-7.851-17.5-17.5-17.5zm-156.145 306.975c-7.508.661-7.872.259-7.216-7.245 1.008-11.517-1.641-18.237-10.536-25.631-6.086-5.061-6.085-5.85 0-10.911 8.895-7.394 11.545-14.114 10.536-25.63-.656-7.505-.292-7.903 7.216-7.246 11.518 1.015 18.229-1.654 25.606-10.561 5.041-6.084 5.812-6.084 10.853 0 7.379 8.907 14.088 11.572 25.606 10.561 7.509-.661 7.872-.259 7.216 7.245-1.008 11.517 1.641 18.236 10.536 25.63 6.085 5.061 6.088 5.852-.001 10.912-8.895 7.395-11.545 14.112-10.536 25.629.659 7.504.293 7.907-7.214 7.247-4.711-.414-10.055-.884-14.759 1.07-4.553 1.891-7.754 5.754-10.849 9.491-1.458 1.76-4.119 4.973-5.427 5.548-1.307-.575-3.968-3.788-5.426-5.548-3.095-3.736-6.295-7.6-10.849-9.491-4.854-1.48-9.773-1.837-14.756-1.07zm34.196 110.914c-2.006-.933-4.32-.933-6.326 0l-10.837 5.041v-92.349c3.559 3.913 7.95 7.546 14 7.546s10.441-3.632 14-7.545v92.348z"/><path d="m97.263 143.321h258.547c9.697 0 9.697-15 0-15h-258.547c-9.698 0-9.698 15 0 15z"/><path d="m422.237 181.439c0-4.142-3.357-7.5-7.5-7.5h-317.474c-9.697 0-9.697 15 0 15h317.475c4.142 0 7.499-3.358 7.499-7.5z"/><path d="m285.137 227.056c0-4.142-3.357-7.5-7.5-7.5h-180.374c-9.697 0-9.697 15 0 15h180.374c4.142 0 7.5-3.358 7.5-7.5z"/><path d="m186.651 331.598c-4.089.007-8.179.012-12.268.015-7.867.006-12.198-2.091-13.805-10.433-1.467-7.615-12.451-6.645-14.464 0-.891 2.943-5.391 11.368-9.322 10.679-2.083-.365-2.993-3.578-3.763-5.242-1.499-3.239-2.591-6.672-3.526-10.112-3.948-14.534-4.736-29.709-7.206-44.517-1.432-8.587-14.521-6.159-14.732 1.994-.672 25.971-4.918 51.586-14.164 75.929-3.431 9.033 11.064 12.939 14.464 3.988 3.565-9.386 6.395-18.997 8.591-28.751 1.178 3.554 2.583 7.04 4.298 10.431 4.38 8.656 13.313 13.777 22.871 9.927 3.411-1.374 6.523-3.776 9.172-6.699 7.096 8.183 17.653 7.812 27.502 7.8 14.847-.019 29.694-.054 44.542-.095 9.651-.027 9.669-15.027 0-15-12.73.036-25.46.065-38.19.086z"/><path d="m369.388 332.874c16.153 0 29.295-13.141 29.295-29.294s-13.142-29.294-29.295-29.294c-16.152 0-29.294 13.142-29.294 29.294s13.141 29.294 29.294 29.294zm0-43.588c7.882 0 14.295 6.413 14.295 14.294s-6.413 14.294-14.295 14.294-14.294-6.412-14.294-14.294 6.412-14.294 14.294-14.294z"/></g>
                </svg>
                <div>{{ trans('laprofile.external_certificate') }}</div>
            </a>
        @endif
        @if(userCan('user-create') || userCan('user-edit') || \App\Models\Permission::isUnitManager())
            <a href="{{route('module.backend.user_medal',[$user_id])}}" class="btn btn_tab_user mb-2">
                <svg class="w_25px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                    <g>
                        <g>
                            <path d="M276.316,202.039l-23.28-19.674l3.011-30.333c0.332-3.349-1.236-6.535-4.093-8.316c-2.856-1.78-6.408-1.785-9.27-0.011    l-25.906,16.06l-27.916-12.235c-3.084-1.352-6.598-0.846-9.175,1.321c-2.577,2.168-3.679,5.546-2.875,8.814l7.269,29.601    l-20.262,22.767c-2.239,2.515-2.844,6.015-1.58,9.136c1.264,3.12,4.136,5.212,7.493,5.458l30.399,2.234l15.394,26.31    c1.592,2.72,4.448,4.344,7.561,4.344c0.211,0,0.425-0.008,0.638-0.022c3.357-0.239,6.233-2.322,7.506-5.44l11.518-28.221    l29.782-6.511c3.287-0.721,5.832-3.199,6.641-6.465C279.98,207.59,278.886,204.212,276.316,202.039z M236.4,209.936    c-2.824,0.618-5.162,2.585-6.253,5.259l-8.03,19.674l-10.735-18.346c-1.459-2.489-4.047-4.102-6.931-4.316l-21.192-1.557    l14.129-15.877c1.919-2.158,2.653-5.121,1.965-7.927l-5.068-20.636l19.457,8.527c2.646,1.163,5.693,0.947,8.155-0.577    l18.061-11.197l-2.098,21.145c-0.286,2.876,0.861,5.707,3.069,7.574l16.23,13.715L236.4,209.936z"/>
                        </g>
                    </g>
                    <g>
                        <g>
                            <path d="M491.943,386.842l-24.885-21.981c-6.347-5.605-16.02-5.722-22.501-0.273l-1.116,0.939c-1.1-3.964-3.11-7.716-6.059-10.892    c-3.603-3.881-8.178-6.536-13.218-7.73c2.907-8.928,1.028-19.133-5.799-26.488c-4.856-5.231-11.458-8.257-18.59-8.52    c-0.215-0.008-0.428-0.005-0.642-0.008c0.327-6.924-2.011-13.968-7.087-19.437c-4.796-5.167-11.297-8.177-18.329-8.505    c0.634-7.264-1.68-14.758-7.019-20.509c-4.856-5.231-11.458-8.257-18.59-8.52c-7.134-0.269-13.94,2.266-19.169,7.123l-3.544,3.292    c-5.148-3.716-11.307-5.826-17.848-6.041c-0.824-0.028-1.643-0.016-2.459,0.016l2.656-8.863    c4.763-15.896,7.727-44.713,7.639-43.167c0.257,0.124,0.509,0.253,0.772,0.37c3.654,1.63,7.591,2.44,11.512,2.44    c4.495,0,8.969-1.065,12.967-3.182c8.323-4.406,14.23-13.616,14.7-22.919c0.447-8.853-3.722-16.67-11.738-22.013    c-3.432-2.285-8.067-1.359-10.353,2.072c-2.286,3.431-1.358,8.066,2.073,10.353c3.565,2.375,5.283,5.347,5.106,8.834    c-0.207,4.102-3.056,8.508-6.774,10.476c-3.409,1.806-7.781,1.921-11.411,0.301c-2.318-1.033-5.323-3.232-6.473-7.835    c-3.425-13.709,4.429-27.316,13.522-43.07c16.471-28.537,36.969-64.05-5.939-114.766l-17.77-21.004    c-3.579-4.231-8.591-6.81-14.113-7.264c-5.522-0.454-10.888,1.272-15.11,4.862c-11.449,9.735-17.401,24.476-15.923,39.431    l0.887,8.972c0.455,4.601-1.317,9.121-4.727,12.215l9.621,11.44c7.179-6.27,10.904-15.622,9.964-25.124l-0.887-8.972    c-0.997-10.084,3.016-20.023,10.736-26.587c1.177-1.001,2.669-1.485,4.215-1.356c1.54,0.126,2.938,0.846,3.937,2.026l17.77,21.004    c36.126,42.701,20.718,69.397,4.406,97.658c-2.552,4.421-5.124,8.883-7.451,13.407c-6.169-21.2-16.494-40.91-30.77-57.883    l-34.17-40.627l0.192-0.161c2.742-2.306,4.422-5.541,4.73-9.11c0.308-3.57-0.792-7.045-3.098-9.786l-10.918-12.981    c-2.306-2.742-5.541-4.422-9.11-4.73c-3.569-0.307-7.044,0.791-9.787,3.097L43.434,161.979    c-5.659,4.759-6.391,13.235-1.632,18.896l10.918,12.981c2.306,2.742,5.541,4.422,9.111,4.73c0.394,0.034,0.786,0.051,1.176,0.051    c3.151,0,6.171-1.097,8.609-3.149l0.193-0.163l12.833,15.259c-3.614,2.784-8.324,3.742-12.744,2.531l-8.693-2.386    c-14.49-3.978-30.025-0.596-41.551,9.049c-4.249,3.557-6.856,8.554-7.339,14.075c-0.484,5.52,1.214,10.894,4.781,15.135    l17.709,21.055c42.759,50.841,81.22,36.623,112.124,25.197c13.947-5.156,26.339-9.736,37.465-8.13    c2.065,0.581,4.143,1.13,6.237,1.636c0.345,0.135,0.689,0.274,1.032,0.424c4.343,1.908,6.004,5.241,6.633,7.7    c0.984,3.85,0.133,8.14-2.221,11.197c-2.566,3.333-7.389,5.394-11.468,4.911c-3.467-0.414-6.107-2.608-7.848-6.522    c-1.675-3.767-6.086-5.461-9.856-3.787c-3.767,1.675-5.463,6.087-3.788,9.855c3.915,8.802,10.919,14.229,19.72,15.28    c0.972,0.116,1.951,0.173,2.934,0.173c8.382-0.001,16.993-4.123,22.134-10.8c5.168-6.71,6.984-15.684,4.857-24.004    c-0.049-0.19-0.108-0.375-0.161-0.563c6.79,0.765,13.678,1.167,20.641,1.167c7.458,0,15.002-0.446,22.584-1.351l0.661-0.079    c-3.4,4.056-6.047,8.718-7.758,13.714l-29.347,72.186L64.838,498.616c-2.534,1.948-3.547,5.295-2.517,8.321    c1.029,3.027,3.871,5.063,7.068,5.063h177.372c2.014,0,3.943-0.814,5.348-2.256l9.879-10.143    c2.877-2.954,2.815-7.681-0.139-10.558c-2.954-2.876-7.681-2.815-10.557,0.139l-7.681,7.887H91.347l140.742-108.217    c0.104-0.08,0.197-0.167,0.295-0.251c0.067-0.057,0.136-0.112,0.201-0.172c0.238-0.218,0.459-0.448,0.663-0.69    c0.04-0.048,0.077-0.099,0.116-0.149c0.186-0.232,0.356-0.473,0.511-0.723c0.029-0.047,0.061-0.092,0.089-0.14    c0.176-0.298,0.328-0.605,0.46-0.921c0.009-0.021,0.022-0.04,0.03-0.061l30.192-74.264c0.058-0.143,0.112-0.288,0.161-0.434    c1.466-4.364,4.027-8.35,7.405-11.53l21.443-20.182c3.682-3.466,8.43-5.286,13.399-5.12c4.691,0.155,9.003,2.096,12.141,5.467    l0.798,0.857l0.001,0.001l0.001,0.001l26.228,28.171c0.001,0.001,0.002,0.002,0.002,0.003c0.001,0.001,0.002,0.002,0.003,0.003    l0.001,0.001c0.002,0.002,0.003,0.004,0.005,0.006s0.004,0.004,0.006,0.006l3.716,3.991c1.566,1.682,2.38,3.904,2.295,6.256    c-0.091,2.526-1.187,4.889-3.084,6.652l-4.323,4.017c-2.991,2.778-7.241,3.303-10.541,1.621c-0.086-0.047-0.176-0.086-0.265-0.13    c-0.766-0.425-1.476-0.973-2.101-1.644l-10.887-11.712c-3.891-4.185-9.207-6.596-14.968-6.788    c-5.903-0.193-11.559,1.947-15.905,6.037c-0.405,0.382-0.763,0.803-1.069,1.256c-0.765,1.132-1.208,2.458-1.272,3.833    c-0.013,0.275-0.01,0.552,0.008,0.83c0.001,0.01,0.001,0.02,0.001,0.03c0.036,0.567,0.061,1.128,0.076,1.686    c0.008,0.314,0.007,0.625,0.009,0.937c0.002,0.24,0.006,0.481,0.003,0.719c-0.004,0.398-0.018,0.792-0.033,1.185    c-0.005,0.143-0.009,0.286-0.016,0.429c-0.022,0.444-0.053,0.885-0.089,1.322c-0.005,0.057-0.009,0.115-0.014,0.172    c-0.021,0.187-0.038,0.374-0.045,0.562c-0.697,6.912-3.195,13.098-7.56,18.763c-2.517,3.266-1.909,7.954,1.357,10.47    c3.265,2.516,7.954,1.908,10.47-1.358c2.586-3.356,4.707-6.891,6.373-10.589l63.499,86.849l-1.515,1.274    c-0.948,0.325-1.847,0.843-2.631,1.565c-5.277,4.855-11.736,8.127-18.699,9.479l-46.751,3.36    c-1.824,0.131-3.537,0.927-4.813,2.237l-11.935,12.254c-2.877,2.954-2.815,7.681,0.139,10.558c1.451,1.413,3.33,2.117,5.208,2.117    c1.943,0,3.885-0.754,5.348-2.256l9.954-10.221l44.347-3.186c0.085-0.006,0.168-0.023,0.252-0.032    c0.193,2.674,0.993,5.325,2.465,7.719l17.39,28.283c2.629,4.276,6.978,7.187,11.932,7.987c0.918,0.149,1.838,0.222,2.753,0.222    c4.028,0,7.958-1.412,11.088-4.045l105.218-88.492c3.841-3.23,6.078-7.961,6.14-12.98    C497.827,394.949,495.704,390.166,491.943,386.842z M143.753,281.236c-30.609,11.316-59.519,22.003-95.519-20.802l-17.709-21.055    c-0.994-1.183-1.468-2.682-1.333-4.222s0.862-2.935,2.048-3.926c7.771-6.503,18.246-8.783,28.016-6.102l8.693,2.386    c9.156,2.511,18.943,0.455,26.319-5.487l11.712,13.926c14.31,17.015,32.008,30.591,51.895,40.307    C153.141,277.767,148.426,279.508,143.753,281.236z M293.439,246.159l-5.637,18.811c-1.536,1.034-3.004,2.195-4.382,3.491    l-7.143,6.723l-20.222,2.416c-54.359,6.495-106.194-12.668-138.649-51.257l-34.169-40.627l13.339-11.219    c3.156-2.654,3.562-7.364,0.908-10.519c-2.653-3.156-7.364-3.565-10.519-0.909l-19.05,16.021    c-0.001,0.001-0.002,0.002-0.003,0.002c-0.001,0.001-0.002,0.002-0.003,0.003l-4.74,3.987l-8.962-10.655L224.421,29.271    l8.962,10.656l-4.733,3.981c-0.003,0.003-0.007,0.005-0.01,0.007c-0.003,0.002-0.006,0.006-0.009,0.008l-114.739,96.5    c-3.155,2.655-3.562,7.364-0.908,10.519c2.653,3.156,7.362,3.56,10.519,0.909l109.034-91.704l34.169,40.627    C299.162,139.365,309.155,193.715,293.439,246.159z M418.439,361.127c3.069,0.114,5.911,1.416,8,3.667    c3.562,3.838,4.05,9.589,1.282,13.954l-32.529,27.359c-3.504,0.294-6.907-0.998-9.34-3.619c-4.311-4.644-4.041-11.93,0.6-16.243    l4.713-4.379c0.861-0.628,1.694-1.305,2.487-2.041l18.206-16.913C413.806,361.673,416.074,361.05,418.439,361.127z     M391.647,329.219c2.049-1.566,4.529-2.41,7.128-2.41c0.148,0,0.297,0.003,0.446,0.008c3.146,0.117,6.058,1.451,8.2,3.758    c4.419,4.759,4.143,12.228-0.615,16.648l-4.3,3.995c-0.859,0.626-1.689,1.299-2.48,2.034l-18.228,16.934    c-2.002,1.283-4.34,1.936-6.764,1.841c-3.146-0.117-6.058-1.451-8.2-3.758c-4.129-4.448-4.156-11.26-0.258-15.74    c0.254-0.219,0.508-0.44,0.755-0.669l23.317-21.662C390.993,329.878,391.324,329.552,391.647,329.219z M352.077,293.09    l-16.057-17.247l3.081-2.863c2.306-2.142,5.308-3.237,8.454-3.142c3.146,0.117,6.058,1.451,8.2,3.758    c4.419,4.759,4.142,12.228-0.616,16.649L352.077,293.09z M355.006,340.587l4.324-4.018c4.821-4.478,7.606-10.534,7.842-17.049    c0.205-5.658-1.555-11.063-4.979-15.432l2.255-2.095c2.306-2.142,5.302-3.264,8.454-3.142c3.145,0.117,6.057,1.451,8.199,3.758    c4.131,4.45,4.156,11.265,0.255,15.746c-0.253,0.218-0.505,0.436-0.751,0.664l-23.317,21.662    c-0.345,0.321-0.679,0.649-1.003,0.983c-1.754,1.342-3.828,2.144-6.017,2.345C351.939,343.073,353.538,341.95,355.006,340.587z     M302.821,328.059c0.022-0.563,0.042-1.125,0.047-1.693c0.215-0.081,0.435-0.151,0.657-0.211c0.667-0.179,1.36-0.26,2.06-0.235    c1.758,0.058,3.366,0.781,4.53,2.032l10.887,11.711c1.114,1.198,2.327,2.253,3.614,3.165c1.199,2.816,2.858,5.376,4.956,7.636    c4.856,5.231,11.458,8.257,18.591,8.521c0.215,0.007,0.428,0.009,0.642,0.012c-0.326,6.922,2.012,13.965,7.087,19.432    c3.638,3.919,8.256,6.6,13.343,7.81c-2.972,8.88-1.133,19.077,5.673,26.408c1.9,2.046,4.092,3.747,6.477,5.072l-8.057,6.776    L302.821,328.059z M482.014,401.521l-105.218,88.492c-0.702,0.592-1.462,0.573-1.849,0.511c-0.389-0.063-1.113-0.285-1.594-1.067    l-17.39-28.284c-0.602-0.979-0.401-2.227,0.479-2.967l97.719-82.187c0.004-0.003,0,0,0.004-0.003    c0.425-0.358,0.954-0.537,1.482-0.537c0.547,0,1.093,0.192,1.524,0.573l24.885,21.981c0.688,0.608,0.783,1.36,0.778,1.755    C482.831,400.181,482.717,400.931,482.014,401.521z"/>
                        </g>
                    </g>
                </svg>
                <div>{{ trans('lacategory.competition_program') }}</div>
            </a>
        @endif

        {{--@can('user-view-training-by-title')
        <a href="{{route('module.backend.user.training_by_title',['user_id'=>$user_id])}}" class="btn ">
            <div><i class="fas fa-chart-line" aria-hidden="true"></i></div>
            <div>Lộ trình đào tạo</div>
        </a>
        @endcan--}}
    </div>
</div>