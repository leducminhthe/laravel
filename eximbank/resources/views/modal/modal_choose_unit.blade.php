<style>
    .wrapped_checkbox_unit {
        width: 100%;
        min-height: 30px;
        border: 1px solid #ccc;
        border-radius: 2px;
        padding: 3px;
    }
    .wrapped_checkbox_unit span {
        color: #343a40;
    }
    .title_check_unit {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 1px 5px;
        margin: 2px 4px;
        display: inline-block;
    }
    .remove_check {
        border-radius: 50%;
        border: 1px solid #dc3545;
        padding: 0px 5px 2px 5px;
        margin-left: 2px;
        color: red !important;
        cursor: pointer;
    }
    .wrapped_checkbox_unit {
        display: none;
    }
</style>
<div class="modal fade" id="modal-choose-unit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="back" onclick="backUnitHandle()">
                    <i class="fas fa-arrow-left"></i>
                </div>
                <h5 class="modal-title ml-2 modal_title_load_unit">Đơn vị</h5>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <input type="hidden" class="old_unit" value="">
                        <input type="hidden" class="level" value="">
                        <input type="hidden" class="page" value="1">
                        @if ($multiple)
                            <div class="wrapped_checkbox_unit mb-2">
                                <span class="title_checkbox ml-2"></span>
                            </div>
                        @endif
                        <input type="text" id="unitSearch" class="form-control" placeholder="Nhập tên đơn vị để tìm kiếm">
                        <div class="list-group checkbox-list-group wrapped_list" style="max-height: 400px;overflow: auto;" id="wrapped_list">
                            <div class="" id="results_unit">
                            </div>
                            <div class="ajax-loading text-center m-3">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @if ($multiple)
                <div class="modal-footer">
                    <button type="button" class="btn" id="select-button" onclick="selectMultipleUnitHandle()"><i class="fa fa-check-circle"></i> Chọn</button>
                </div>
            @endif
        </div>
    </div>
</div>
<script>
    $(document).on("keydown", "form", function(event) {
        return event.key != "Enter";
    });

    $("#unitSearch").on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            var multiple = $('#multiple_unit').val()
            var unitIdSelected = $('#search_unit_id').val()
            var search = $(this).val();
            if (search) {
                $('.ajax-loading').show();
                $('#results_unit').hide();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('search_unit_modal') }}' + "?search=" + search + "&multiple=" + multiple + "&unitIdSelected=" + unitIdSelected,
                    data: {'level':$('.level').val()},
                    dataType: 'html'
                }).done(function(data) {
                    $('.ajax-loading').hide();
                    $('#results_unit').show();
                    $("#results_unit").html(data);
                }).fail(function(data) {
                    item.html(oldtext);
                    show_message('{{ trans('laother.data_error') }}', 'error');
                    return false;
                });
            } else {
                $("#results_unit").html('');
                let userUnit = $('.element_data').attr("data-user_unit")
                let unitId = $('.old_unit').val();
                let level = $('.level').val();
                let userUnitSearch = unitId ? unitId : userUnit;
                let type_search = (level > 1) ? 2 : 1;
                load_choose_unit(1, userUnitSearch, type_search, 0);
            }
        }
    });

    var wrapped_list = document.getElementById('wrapped_list')
    wrapped_list.addEventListener('scroll', function(event)
    {
        var element = event.target;
        var loadMore = 1;
        var getPage = $('.page').val();
        if (element.scrollHeight - element.scrollTop <= (element.clientHeight + 2))
        {
            var id = $('.old_unit').val() ? $('.old_unit').val() : 0;
            if(empty == 0) {
                getPage++;
                $('.page').val(getPage)
                load_choose_unit(getPage, id, 2, loadMore);
            }
        }
    });
</script>
