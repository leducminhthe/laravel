@extends('themes.mobile.layouts.app')

@section('page_title', data_locale('Chứng chỉ bên ngoài', 'My certificate'))

@section('content')
    <div class="container wrraped_my_certificate mt-2">
        <div class="row">
            @if ($checkUser == 0)
                <div class="col-12 mb-3 text-right">
                    <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.front.my_certificate.create') }}', 1, 3)" class="btn">
                        <i class="fa fa-edit"></i> Thêm chứng chỉ
                    </a>
                </div>
            @endif
            <div class="col-12">
                <div class="list-group list-group-flush" id="training-process">
                    @if(count($rows) > 0)
                        @foreach($rows as $item)
                            <div class="row mb-2 bg-white shadow border">
                                <div class="col-12 info my-1">
                                    <div class="row d_flex_align">
                                        <div class="col-11">
                                            @if ($checkUser == 0)
                                            <a href="javascript:void(0);" onclick="loadSpinner('{{ route('themes.mobile.front.my_certificate.edit', ['id' => $item->id]) }}')">
                                            @else
                                            <a href="">
                                            @endif
                                                <p class="mb-0">
                                                    Chứng chỉ: {{ $item->name_certificate }}
                                                </p>
                                            </a>
                                        </div>
                                        @if ($checkUser == 0)
                                            <div class="col-1 text-right px-1">
                                                <a href="javascript:void(0)" onclick="deleteCertificateHandle({{ $item->id }})">
                                                    <i class="fa fa-trash" style="font-size: 18px"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="mb-0">
                                        Trường: {{ $item->name_school }}
                                    </p>
                                    <div class="row">
                                        <div class="col-6">
                                            {{ data_locale('Thời gian học', 'Time learn') }}: {{ $item->time_start }} 
                                        </div>
                                        <div class="col-6 text-right">
                                            {{ data_locale('Ngày cấp', 'date license') }}: {{ $item->date_license }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            {{ data_locale('Điểm', 'Score') }}: {{ $item->score }} 
                                        </div>
                                        <div class="col-6 text-right">
                                            {{ data_locale('Kết quả', 'Result') }}: {{ $item->result }}
                                        </div>
                                    </div>
                                    <p class="mb-0 d_flex_align">
                                        <input type="hidden" class="value_img_certificate_{{ $item->id }}" value="{{ $item->img }}">
                                        <span>{{ trans('laprofile.certificate_image') }}:</span>
                                        <a href="javascript:void(0)" onclick="showCertificateHandle({{ $item->id }})">
                                            <i class="fas fa-images ml-2" style="font-size: 20px"></i>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                        <div class="row mt-2">
                            <div class="col-12 px-0 text-right">
                                {{ $rows->links('themes/mobile/layouts/pagination') }}
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-12 text-center">
                                <span>@lang('app.not_found')</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    {{-- MODAL HÌNH CHỨNG CHỈ --}}
    <div id="modal-img-cetificate" class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <h6 class="modal-title">{{ trans('app.your_QR_code') }}</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div> --}}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <img src="" id="img_certificate" alt="" width="100%" height="100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        function showCertificateHandle(id) {
            var valueImg = $('.value_img_certificate_'+ id).val();
            $('#img_certificate').attr('src', valueImg);
            $('#modal-img-cetificate').modal();
        }

        function deleteCertificateHandle(id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('themes.mobile.front.my_certificate.delete') }}',
                dataType: 'json',
                data: {
                    id: id,
                }
            }).done(function (data) {
                show_message(data.message, data.status);
                window.location = data.redirect;
            }).fail(function (data) {
                show_message('Không thể xoá tài khoản của bạn', 'error');
            });
        }
    </script>
@endsection

