<div class="modal fade" id="modal_get_promotion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border">
            <form action="{{ route('themes.mobile.front.promotion.get') }}" method="post" class="form-ajax">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thông tin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" class="promotionId">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <p class='mb-1'>Địa điểm nhận quà</p>
                            <input name="location" type="text" class="form-control" placeholder="Địa điểm" required>
                        </div>
                        <div class="col-12 mb-2">
                            <p class='mb-1'>Số diện thoại</p>
                            <input name="phoneNumber" type="number" class="form-control" placeholder="Số diện thoại" required>
                        </div>
                        <div class="col-12 mb-2">
                            <p class='mb-1'>Khoảng thời gian nhận</p>
                            <select name="time" class="select2 form-control" data-placeholder="Chọn thời gian">
                                <option value=""></option>
                                <option value="1">Sáng (6h-12)</option>
                                <option value="2">Chiều (1-5h)</option>
                                <option value="3">Tối (6-8h)</option>
                            </select>
                            <input name="dateFrom" type="text" class="datepicker form-control d-inline-block my-1" placeholder="{{trans('laother.choose_start_date')}}" required>
                            <input name="dateTo" type="text" class="datepicker form-control d-inline-block" placeholder="{{trans('laother.choose_end_date')}}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn w-100 p-2">Đổi quà</button>
                </div>
            </form>
        </div>
    </div>
</div>